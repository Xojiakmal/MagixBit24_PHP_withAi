<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantIsolated;
use App\Traits\HasCustomFields;

class ContactGroup extends Model
{
    use HasFactory, TenantIsolated, HasCustomFields;

    protected $fillable = [
        'name',
        'tenant_id'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'contact_group_id');
    }
}
