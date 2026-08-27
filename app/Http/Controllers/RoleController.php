<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\ApprovalRequest;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = Tenant::findOrFail($user->current_tenant_id);
        
        $users = User::whereHas('tenants', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)->where('status', 'active');
        })->get();
        
        $approvalRequests = ApprovalRequest::where('tenant_id', $tenant->id)
                                ->where('status', 'pending')
                                ->get();
                                
        return view('company.roles', compact('users', 'tenant', 'approvalRequests'));
    }
    
    public function assignRole(Request $request, User $targetUser)
    {
        $request->validate(['role' => 'required|in:Admin,Collaborator,Xodim']);
        
        $user = Auth::user();
        $roleToAssign = $request->role;
        
        // Basic check if the current user is an Admin
        if (!$user->hasRole('Admin') && !$user->is_superadmin) {
            return back()->with('error', 'Sizda bu huquq yo\'q.');
        }
        
        // If promoting to Collaborator or Admin
        $targetUser->syncRoles([$roleToAssign]);
        
        return back()->with('success', 'Rol muvaffaqiyatli o\'zgartirildi.');
    }
    
    public function demoteCollaborator(Request $request, User $targetUser)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Admin') && !$user->is_superadmin) {
            return back()->with('error', 'Sizda bu huquq yo\'q.');
        }
        
        if (!$targetUser->hasRole('Collaborator')) {
            return back()->with('error', 'Bu foydalanuvchi Collaborator emas.');
        }
        
        // Create an approval request for the Collaborator to accept their demotion
        ApprovalRequest::create([
            'tenant_id' => $user->current_tenant_id,
            'requester_id' => $user->id,
            'action' => 'demote_collaborator',
            'payload' => ['target_user_id' => $targetUser->id],
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'Lavozimni tushirish bo\'yicha so\'rov yuborildi. Collaborator buni tasdiqlashi kerak.');
    }
    
    public function approveRequest(Request $request, ApprovalRequest $approvalRequest)
    {
        $user = Auth::user();
        
        if ($approvalRequest->action === 'demote_collaborator') {
            if ($user->id !== $approvalRequest->payload['target_user_id']) {
                return back()->with('error', 'Faqatgina o\'zingizni lavozimdan tushirishni tasdiqlashingiz mumkin.');
            }
            
            // Accept the demotion
            $targetUser = User::find($user->id);
            $targetUser->syncRoles(['Xodim']);
            
            $approvalRequest->update([
                'status' => 'approved',
                'approver_id' => $user->id
            ]);
            
            return back()->with('success', 'Lavozimdan tushishni tasdiqladingiz.');
        }
        
        return back()->with('error', 'Noma\'lum harakat.');
    }
}
