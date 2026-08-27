<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasActivities
{
    public static function bootHasActivities()
    {
        static::created(function ($model) {
            $model->logActivity('created', class_basename($model) . ' yaratildi');
        });

        static::updated(function ($model) {
            $newValues = $model->getDirty();
            $oldValues = [];
            foreach ($newValues as $key => $value) {
                // don't log updated_at changes
                if ($key === 'updated_at') continue;
                $oldValues[$key] = $model->getOriginal($key);
            }
            
            // Only log if there are actual changes beyond updated_at
            if (count($oldValues) > 0) {
                $model->logActivity(
                    'updated', 
                    class_basename($model) . ' tahrirlandi',
                    $oldValues,
                    $newValues
                );
            }
        });
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function logActivity(string $type, string $description = null, array $oldValues = null, array $newValues = null)
    {
        $tenantId = $this->tenant_id ?? (Auth::user() ? Auth::user()->current_tenant_id : null);
        
        if ($tenantId) {
            $this->activities()->create([
                'tenant_id' => $tenantId,
                'user_id' => Auth::id(),
                'type' => $type,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
            ]);

            \App\Jobs\ArchiveActivitiesJob::dispatch($tenantId);
        }
    }
}
