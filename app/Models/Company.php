<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;
use App\Traits\HasActivities;
use App\Traits\HasCustomFields;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Company extends Model
{
    use TenantIsolated, HasActivities, HasCustomFields;
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
