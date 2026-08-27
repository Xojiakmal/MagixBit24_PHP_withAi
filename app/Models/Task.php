<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use TenantIsolated;
    
    protected $fillable = ['deal_id', 'title', 'description', 'status', 'priority', 'start_date', 'due_date', 'assigned_to', 'assigned_to_department_id', 'assigned_to_everyone', 'created_by', 'observers', 'available_to_everyone'];

    protected $casts = [
        'observers' => 'array',
        'available_to_everyone' => 'boolean',
        'assigned_to_everyone' => 'boolean',
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
