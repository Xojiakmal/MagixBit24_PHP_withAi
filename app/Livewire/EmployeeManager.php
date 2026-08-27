<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class EmployeeManager extends Component
{
    public $tenant;
    public $activeTab = 'employees'; // 'employees' or 'roles'
    
    // Role creation/editing properties
    public $roleId = null;
    public $roleName = '';
    public $rolePermissions = [];
    public $isEditingRole = false;

    public $permissionsList = [
        'Bosh sahifa' => [
            'view_dashboard' => 'Bosh sahifaga kirish',
        ],
        'CRM (Bitimlar)' => [
            'view_crm' => 'Bitimlarni ko\'rish',
            'create_deal' => 'Yangi bitim qo\'shish',
            'comment_deal' => 'Bitimga izoh yozish',
        ],
        'Vazifalar (Tasks)' => [
            'view_tasks' => 'Vazifalarni ko\'rish',
            'create_task' => 'Vazifa yaratish',
            'edit_task' => 'Vazifani tahrirlash',
            'delete_task' => 'Vazifani o\'chirish',
        ],
        'Omborxona (Storage)' => [
            'view_storage' => 'Omborxonani ko\'rish',
            'manage_storage' => 'Omborxonani boshqarish',
        ],
        'Xodimlar' => [
            'manage_employees' => 'Xodimlarni boshqarish',
        ]
    ];
    
    public function mount()
    {
        $this->tenant = Tenant::findOrFail(Auth::user()->current_tenant_id);

        // Auto-seed permissions if missing
        $permissions = [
            'view_dashboard', 'view_crm', 'create_deal', 'comment_deal',
            'view_tasks', 'create_task', 'edit_task', 'delete_task',
            'view_storage', 'manage_storage', 'manage_employees'
        ];
        foreach ($permissions as $p) {
            \Spatie\Permission\Models\Permission::findOrCreate($p, 'web');
        }
    }

    // Teams Management
    public $teamName = '';
    public $teamManagerId = null;
    public $teamMembers = [];
    public $isEditingTeam = false;
    public $teamId = null;

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Teams
    public function saveTeam()
    {
        $this->authorizeAdmin();
        $this->validate(['teamName' => 'required|string|max:255']);

        if ($this->isEditingTeam && $this->teamId) {
            $team = \App\Models\Team::findOrFail($this->teamId);
            $team->update([
                'name' => $this->teamName,
                'manager_id' => $this->teamManagerId ?: null
            ]);
            
            // Assign members
            \App\Models\User::where('team_id', $team->id)->update(['team_id' => null]);
            \App\Models\User::whereIn('id', $this->teamMembers)->update(['team_id' => $team->id]);
        } else {
            $team = \App\Models\Team::create([
                'name' => $this->teamName,
                'manager_id' => $this->teamManagerId ?: null,
                'tenant_id' => $this->tenant->id
            ]);
            \App\Models\User::whereIn('id', $this->teamMembers)->update(['team_id' => $team->id]);
        }

        $this->resetTeamForm();
    }

    public function editTeam($id)
    {
        $this->authorizeAdmin();
        $team = \App\Models\Team::findOrFail($id);
        $this->teamId = $team->id;
        $this->teamName = $team->name;
        $this->teamManagerId = $team->manager_id;
        $this->teamMembers = $team->users->pluck('id')->toArray();
        $this->isEditingTeam = true;
    }

    public function deleteTeam($id)
    {
        $this->authorizeAdmin();
        \App\Models\Team::findOrFail($id)->delete();
        $this->resetTeamForm();
    }

    public function resetTeamForm()
    {
        $this->teamId = null;
        $this->teamName = '';
        $this->teamManagerId = null;
        $this->teamMembers = [];
        $this->isEditingTeam = false;
    }

    public function toggleTeamMember($userId)
    {
        if (in_array($userId, $this->teamMembers)) {
            $this->teamMembers = array_diff($this->teamMembers, [$userId]);
        } else {
            $this->teamMembers[] = $userId;
        }
    }

    // Assign Role to User
    public function assignRoleToUser($userId, $roleName)
    {
        $this->authorizeManager();
        $user = User::findOrFail($userId);
        
        // Remove all current roles (except Admin if they shouldn't be touched, but we assume we only touch non-admins in UI)
        if ($user->hasRole('Admin')) {
            return; // Can't change admin role from here
        }

        // If empty role selected, just give them basic 'Xodim'
        if (empty($roleName)) {
            $user->syncRoles(['Xodim']);
            return;
        }

        $user->syncRoles([$roleName]);
    }

    // Role Management
    public function toggleRolePermission($permissionKey)
    {
        if (in_array($permissionKey, $this->rolePermissions)) {
            $this->rolePermissions = array_diff($this->rolePermissions, [$permissionKey]);
        } else {
            $this->rolePermissions[] = $permissionKey;
        }
    }

    public function saveRole()
    {
        $this->authorizeAdmin();
        $this->validate([
            'roleName' => 'required|string|max:255',
        ]);

        if ($this->isEditingRole && $this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->name = $this->roleName;
            $role->save();
        } else {
            $role = Role::create([
                'name' => $this->roleName,
                'guard_name' => 'web',
                'tenant_id' => $this->tenant->id
            ]);
        }

        // Sync permissions
        $role->syncPermissions($this->rolePermissions);

        $this->resetRoleForm();
    }

    public function editRole($id)
    {
        $this->authorizeAdmin();
        $role = Role::findOrFail($id);
        if ($role->name === 'Admin' || $role->name === 'Xodim') return; // Cannot edit system roles

        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->rolePermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditingRole = true;
    }

    public function deleteRole($id)
    {
        $this->authorizeAdmin();
        $role = Role::findOrFail($id);
        if ($role->name === 'Admin' || $role->name === 'Xodim') return;
        
        $role->delete();
        $this->resetRoleForm();
    }

    public function resetRoleForm()
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->rolePermissions = [];
        $this->isEditingRole = false;
    }

    private function authorizeAdmin()
    {
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->can('manage_employees')) {
            abort(403, 'Sizda bu amalni bajarish huquqi yo\'q');
        }
    }

    private function authorizeManager()
    {
        // Managers can edit roles of their own team members (or Admin)
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->can('manage_employees') && !Auth::user()->managerOf) {
            abort(403, 'Sizda bu amalni bajarish huquqi yo\'q');
        }
    }

    public function render()
    {
        $query = User::whereHas('tenants', function($q) {
            $q->where('tenant_users.tenant_id', $this->tenant->id)
              ->where('tenant_users.status', 'approved');
        });

        // Filter users if the current user is a manager (and not admin)
        if (!Auth::user()->hasRole('Admin') && Auth::user()->managerOf) {
            $query->where('team_id', Auth::user()->managerOf->id);
        }

        $users = $query->get();
        
        $roles = Role::whereNotIn('name', ['Admin', 'Xodim'])->get();
        $teams = \App\Models\Team::where('tenant_id', $this->tenant->id)->with('users', 'manager')->get();

        return view('livewire.employee-manager', compact('users', 'roles', 'teams'))
            ->layout('layouts.app');
    }
}
