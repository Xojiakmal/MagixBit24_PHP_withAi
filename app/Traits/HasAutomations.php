<?php

namespace App\Traits;

use App\Services\AutomationEngine;

trait HasAutomations
{
    public static function bootHasAutomations()
    {
        static::created(function ($model) {
            AutomationEngine::processEvent($model, 'created');
        });

        static::updated(function ($model) {
            $changes = $model->getDirty();
            
            // General update event
            AutomationEngine::processEvent($model, 'updated');

            // Specific stage_changed event
            if (isset($changes['pipeline_stage_id']) || isset($changes['status'])) {
                AutomationEngine::processEvent($model, 'stage_changed');
            }
        });
    }
}
