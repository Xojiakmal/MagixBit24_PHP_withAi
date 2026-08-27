<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
        $pendingCompanies = Tenant::where('status', 'pending')->with('owner')->get();
        return view('superadmin.index', compact('pendingCompanies'));
    }

    public function approveCompany(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['status' => 'active']);

        $tenantUser = TenantUser::where('tenant_id', $tenant->id)->where('user_id', $tenant->owner_id)->first();
        if ($tenantUser) {
            $tenantUser->update(['status' => 'approved']);
        }

        $owner = User::find($tenant->owner_id);
        if ($owner) {
            if (!$owner->current_tenant_id) {
                $owner->update(['current_tenant_id' => $tenant->id]);
            }
            $owner->assignRole('Admin');
        }

        return redirect()->back()->with('success', 'Kompaniya tasdiqlandi!');
    }

    public function rejectCompany(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        TenantUser::where('tenant_id', $tenant->id)->delete();
        $tenant->delete();

        return redirect()->back()->with('success', 'Kompaniya rad etildi!');
    }
}
