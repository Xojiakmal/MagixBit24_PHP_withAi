<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Pipeline extends Model
{
    use TenantIsolated;
    public function stages(): HasMany
    {
        return $this->hasMany(PipelineStage::class)->orderBy('order');
    }
}
