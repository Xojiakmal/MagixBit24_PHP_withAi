<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;
use App\Traits\HasActivities;
use App\Traits\HasCustomFields;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use TenantIsolated, HasActivities, HasCustomFields;

    protected $fillable = ['company_id', 'contact_group_id', 'tenant_id', 'name', 'phone', 'email'];
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
