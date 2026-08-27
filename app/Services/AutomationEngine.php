<?php

namespace App\Services;

use App\Models\AutomationRule;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AutomationEngine
{
    public static function processEvent(Model $model, string $event)
    {
        $tenantId = $model->tenant_id ?? (auth()->user() ? auth()->user()->current_tenant_id : null);
        if (!$tenantId) {
            return; // No tenant context
        }

        $rules = AutomationRule::where('tenant_id', $tenantId)
            ->where('model_type', get_class($model))
            ->where('trigger_event', $event)
            ->where('is_active', true);
        
        // If the model has a pipeline_stage_id, we can filter rules that are specific to it
        if (isset($model->pipeline_stage_id)) {
            $rules->where(function($q) use ($model) {
                $q->whereNull('pipeline_stage_id')
                  ->orWhere('pipeline_stage_id', $model->pipeline_stage_id);
            });
        }

        $rules = $rules->with('actions')->get();

        foreach ($rules as $rule) {
            if (self::checkConditions($model, $rule->conditions)) {
                foreach ($rule->actions as $action) {
                    self::executeAction($model, $action);
                }
            }
        }
    }

    protected static function checkConditions(Model $model, ?array $conditions): bool
    {
        if (empty($conditions)) return true;
        
        // Example condition check: ['amount' => ['>', 1000]]
        // Simple implementation for now
        foreach ($conditions as $field => $rule) {
            if (is_array($rule) && count($rule) == 2) {
                $operator = $rule[0];
                $value = $rule[1];
                $modelValue = $model->{$field} ?? null;
                
                switch ($operator) {
                    case '>': if (!($modelValue > $value)) return false; break;
                    case '<': if (!($modelValue < $value)) return false; break;
                    case '=': if ($modelValue != $value) return false; break;
                    case '!=': if ($modelValue == $value) return false; break;
                }
            }
        }
        
        return true;
    }

    protected static function executeAction(Model $model, $action)
    {
        try {
            switch ($action->action_type) {
                case 'create_task':
                    Task::create([
                        'tenant_id' => $model->tenant_id,
                        'title' => $action->payload['title'] ?? 'Auto Task',
                        'description' => $action->payload['description'] ?? 'Created by automation rule',
                        'assigned_to' => $model->assigned_to ?? auth()->id(),
                        'deal_id' => $model->deal_id ?? null,
                        'status' => 'to_do',
                    ]);
                    break;
                case 'change_field':
                    if (isset($action->payload['field']) && isset($action->payload['value'])) {
                        $model->{$action->payload['field']} = $action->payload['value'];
                        $model->saveQuietly(); // prevent infinite loop
                    }
                    break;
                case 'log_activity':
                    if (method_exists($model, 'logActivity')) {
                        $model->logActivity('note', $action->payload['message'] ?? 'Automation rule triggered');
                    }
                    break;
                default:
                    Log::info("Unknown automation action: " . $action->action_type);
            }
        } catch (\Exception $e) {
            Log::error("Automation Action Failed: " . $e->getMessage());
        }
    }
}
