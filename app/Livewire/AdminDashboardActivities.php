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
