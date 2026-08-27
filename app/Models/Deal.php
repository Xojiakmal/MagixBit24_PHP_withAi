<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;
use App\Traits\HasActivities;
use App\Traits\HasCustomFields;
use App\Traits\HasAutomations;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    use TenantIsolated, HasActivities, HasCustomFields, HasAutomations;

    protected $fillable = ['title', 'contact_id', 'pipeline_stage_id', 'amount', 'close_date', 'assigned_to', 'start_date', 'end_date', 'company_id', 'deal_type', 'source', 'source_info', 'observers', 'description', 'available_to_everyone', 'assigned_users', 'assigned_roles', 'assigned_teams'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }



    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected function casts(): array
    {
        return [
            'observers' => 'array',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'available_to_everyone' => 'boolean',
            'assigned_users' => 'array',
            'assigned_roles' => 'array',
            'assigned_teams' => 'array',
        ];
    }

    public function hasAccess($user)
    {
        if ($user->hasRole('Admin') || $this->available_to_everyone) {
            return true;
        }

        $assignedUsers = $this->assigned_users ?? [];
        if (in_array($user->id, $assignedUsers)) {
            return true;
        }

        $assignedRoles = $this->assigned_roles ?? [];
        foreach ($user->roles as $role) {
            if (in_array($role->id, $assignedRoles) || in_array($role->name, $assignedRoles)) {
                return true;
            }
        }

        $assignedTeams = $this->assigned_teams ?? [];
        if ($user->team_id && in_array($user->team_id, $assignedTeams)) {
            return true;
        }

        return false;
    }
}
