<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'unit', 'price', 'tenant_id'])]
class Product extends Model
{
    use TenantIsolated;
}
