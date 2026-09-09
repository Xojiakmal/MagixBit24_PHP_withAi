<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Activity;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class AdminDashboardActivities extends Component
{
    use WithFileUploads;

    public $isExpandedActivity = false;
    public $isExpandedHistory = false;
    public $logFile;
    
    // Auto-refresh every 5 seconds (wire:poll handled in blade)
    
    public function restoreLogs()
    {
        $this->validate([
            'logFile' => 'required|file|max:10240', // 10MB max
        ]);

        $content = file_get_contents($this->logFile->getRealPath());
        $activities = json_decode($content, true);

        if (is_array($activities)) {
            $tenantId = Auth::user()->current_tenant_id;
            foreach ($activities as $act) {
                // Ensure we don't duplicate and only restore for current tenant
                if (isset($act['tenant_id']) && $act['tenant_id'] == $tenantId) {
                    Activity::firstOrCreate(
                        ['id' => $act['id']],
                        $act
                    );
                }
            }
            session()->flash('message', 'Tarix muvaffaqiyatli tiklandi!');
        } else {
            session()->flash('error', 'Fayl formati noto\'g\'ri.');
        }

        $this->reset('logFile');
    }
    
    public function expandActivity()
    {
        $this->isExpandedActivity = true;
    }

    public function collapseActivity()
    {
        $this->isExpandedActivity = false;
    }

    public function expandHistory()
    {
        $this->isExpandedHistory = true;
    }

    public function collapseHistory()
    {
        $this->isExpandedHistory = false;
    }

    public function getActivityDetails($activity)
    {
        $user = $activity->user->name ?? 'System';
        $subject = class_basename($activity->subject_type);
        $id = $activity->subject_id;
        $action = $activity->action ?? $activity->type;

        $severity = 'Medium';
        $color = 'text-blue-400';
        $badgeBg = 'bg-blue-400/10';

        $actionText = 'interacted with';

        switch ($action) {
            case 'created':
                $actionText = 'created a new';
                $severity = 'Medium';
                $color = 'text-green-400';
                $badgeBg = 'bg-green-400/10';
                break;
            case 'deleted':
                $actionText = 'deleted a';
                $severity = 'High';
                $color = 'text-red-400';
                $badgeBg = 'bg-red-400/10';
                break;
            case 'assigned':
                $actionText = 'assigned a';
                $severity = 'Medium';
                $color = 'text-blue-400';
                $badgeBg = 'bg-blue-400/10';
                break;
            case 'status_changed':
                $actionText = 'changed the status of';
                $severity = 'High';
                $color = 'text-yellow-400';
                $badgeBg = 'bg-yellow-400/10';
                break;
        }

        $message = "{$user} <span class='font-semibold text-white'>{$actionText}</span> {$subject} (#{$id})";

        return [
            'message' => $message,
            'severity' => $severity,
            'color' => $color,
            'badgeBg' => $badgeBg
        ];
    }

    private function resolveName($key, $id)
    {
        if (empty($id) || $id === 'empty') return $id;

        // Agar $id array bo'lsa
        if (is_array($id)) {
            $resolved = [];
            foreach ($id as $singleId) {
                $resolved[] = $this->resolveName($key, $singleId);
            }
            return '[' . implode(', ', $resolved) . ']';
        }

        // Agar $id JSON array ko'rinishidagi string bo'lsa
        if (is_string($id) && str_starts_with(trim($id), '[') && str_ends_with(trim($id), ']')) {
            $decoded = json_decode($id, true);
            if (is_array($decoded)) {
                return $this->resolveName($key, $decoded);
            }
        }

        if (!is_numeric($id)) return $id;

        try {
            switch ($key) {
                case 'pipeline_stage_id':
                case 'stage_id':
                    return \App\Models\PipelineStage::find($id)?->name ?? $id;
                case 'user_id':
                case 'assigned_to':
                case 'manager_id':
                case 'assigned_users':
                    return \App\Models\User::find($id)?->name ?? $id;
                case 'contact_id':
                    $contact = \App\Models\Contact::find($id);
                    return $contact ? trim($contact->first_name . ' ' . $contact->last_name) : $id;
                case 'team_id':
                case 'assigned_teams':
                    return \App\Models\Team::find($id)?->name ?? $id;
                case 'pipeline_id':
                    return \App\Models\Pipeline::find($id)?->name ?? $id;
                case 'assigned_roles':
                    return \App\Models\Role::find($id)?->name ?? $id; // Using App\Models\Role or Spatie's model
                default:
                    return $id;
            }
        } catch (\Exception $e) {
            return $id;
        }
    }

    public function getHistoryDetails($history)
    {
        $user = $history->user->name ?? 'System';
        $subject = class_basename($history->subject_type);
        $id = $history->subject_id;

        $oldVals = is_string($history->old_values) ? json_decode($history->old_values, true) : $history->old_values;
        $newVals = is_string($history->new_values) ? json_decode($history->new_values, true) : $history->new_values;

        $keys = array_unique(array_merge(array_keys($oldVals ?? []), array_keys($newVals ?? [])));
        $changes = [];

        $severity = 'Low';
        $color = 'text-gray-400';
        $badgeBg = 'bg-gray-400/10';

        foreach ($keys as $key) {
            if (in_array($key, ['updated_at', 'created_at', 'id', 'tenant_id'])) continue;

            $oVal = $this->resolveName($key, $oldVals[$key] ?? 'empty');
            $nVal = $this->resolveName($key, $newVals[$key] ?? 'empty');
            
            if (is_array($oVal)) $oVal = json_encode($oVal, JSON_UNESCAPED_UNICODE);
            if (is_array($nVal)) $nVal = json_encode($nVal, JSON_UNESCAPED_UNICODE);

            $keyName = ucfirst(str_replace('_id', '', str_replace('_', ' ', $key)));

            if (in_array($key, ['status', 'pipeline_stage_id', 'amount', 'stage'])) {
                $severity = 'High';
                $color = 'text-purple-400';
                $badgeBg = 'bg-purple-400/10';
            } elseif ($severity !== 'High' && in_array($key, ['assigned_to', 'team_id', 'contact_id'])) {
                $severity = 'Medium';
                $color = 'text-blue-400';
                $badgeBg = 'bg-blue-400/10';
            }

            $changes[] = "<span class='font-medium text-gray-300'>{$keyName}</span> from <span class='line-through text-red-400/80'>".\Illuminate\Support\Str::limit((string)$oVal, 20)."</span> to <span class='text-green-400/90'>".\Illuminate\Support\Str::limit((string)$nVal, 20)."</span>";
        }

        if (empty($changes)) {
            $message = "{$user} <span class='font-semibold text-white'>updated</span> {$subject} (#{$id}) but no visible changes were recorded.";
        } else {
            // Limit to first 3 changes for brevity, append count if more
            if (count($changes) > 3) {
                $extra = count($changes) - 3;
                $changesStr = implode(', ', array_slice($changes, 0, 3)) . " <span class='text-xs italic text-gray-500'>+{$extra} more</span>";
            } else {
                $changesStr = implode(', ', $changes);
            }
            $message = "{$user} <span class='font-semibold text-white'>updated</span> {$subject} (#{$id}): " . $changesStr;
        }

        return [
            'message' => $message,
            'severity' => $severity,
            'color' => $color,
            'badgeBg' => $badgeBg
        ];
    }

    public function render()
    {
        $tenantId = Auth::user()->current_tenant_id;
        
        // General Activities: creation, deletion, or specific generic actions
        $activities = Activity::where('tenant_id', $tenantId)
            ->whereIn('type', ['created', 'deleted', 'assigned', 'status_changed', 'custom'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take($this->isExpandedActivity ? 100 : 10)
            ->get();
            
        // History: updates with old_values / new_values
        $history = Activity::where('tenant_id', $tenantId)
            ->where('type', 'updated')
            ->where(function($q) {
                $q->whereNotNull('old_values')->orWhereNotNull('new_values');
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take($this->isExpandedHistory ? 100 : 10)
            ->get();

        return view('livewire.admin-dashboard-activities', compact('activities', 'history'));
    }
}
