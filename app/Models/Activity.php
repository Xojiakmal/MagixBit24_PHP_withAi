<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\TenantIsolated;

#[Fillable(['tenant_id', 'user_id', 'subject_type', 'subject_id', 'type', 'description', 'old_values', 'new_values'])]
class Activity extends Model
{
    use TenantIsolated;

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::created(function ($activity) {
            $count = self::where('tenant_id', $activity->tenant_id)->count();
            if ($count >= 1000) {
                dispatch(function () use ($activity) {
                    \App\Services\ActivityArchiver::archive($activity->tenant_id);
                })->afterResponse();
            }
        });
    }
}
