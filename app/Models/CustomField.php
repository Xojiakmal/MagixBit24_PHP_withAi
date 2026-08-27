<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\TenantIsolated;

class CustomField extends Model
{
    use TenantIsolated;

    protected $fillable = ['tenant_id', 'model_type', 'contact_group_id', 'name', 'type', 'is_required', 'options'];

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
