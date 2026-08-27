<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\TenantIsolated;

#[Fillable(['tenant_id', 'model_type', 'trigger_event', 'pipeline_stage_id', 'conditions', 'is_active'])]
class AutomationRule extends Model
{
    use TenantIsolated;

    protected $casts = [
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(AutomationAction::class)->orderBy('order');
    }
}
