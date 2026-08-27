<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait TenantIsolated
{
    protected static function bootTenantIsolated()
    {
        static::addGlobalScope('tenant_isolated', function (Builder $builder) {
            $user = Auth::user();
            
            // If running in console (e.g. migrations) or no user is logged in, don't scope.
            if (app()->runningInConsole() || !$user) {
                return;
            }

            // SuperAdmins might bypass it if they are viewing all, but usually we scope everything.
            // If we want SuperAdmins to bypass, uncomment the below lines:
            // if ($user->is_superadmin && request()->routeIs('superadmin.*')) {
            //     return;
            // }

            if ($user->current_tenant_id) {
                $builder->where('tenant_id', $user->current_tenant_id);
            } else {
                $builder->whereRaw('1 = 0');
            }
        });

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user && $user->current_tenant_id && !isset($model->tenant_id)) {
                $model->tenant_id = $user->current_tenant_id;
            }
        });
    }
}
