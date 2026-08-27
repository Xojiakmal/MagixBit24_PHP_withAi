<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check() && Auth::user()->current_tenant_id) {
                // If it's a global role like 'Admin', tenant_id is null.
                // Otherwise, it belongs to the current tenant.
                $builder->where(function ($query) {
                    $query->whereNull('tenant_id')
                          ->orWhere('tenant_id', Auth::user()->current_tenant_id);
                });
            }
        });
        
        static::creating(function ($role) {
            if (Auth::check() && Auth::user()->current_tenant_id) {
                if (!isset($role->tenant_id) && $role->name !== 'Admin' && $role->name !== 'Xodim') {
                    $role->tenant_id = Auth::user()->current_tenant_id;
                }
            }
        });
    }
}
