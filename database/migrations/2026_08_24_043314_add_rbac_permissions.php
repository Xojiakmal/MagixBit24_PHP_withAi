<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_crm',
            'view_tasks',
            'view_storage',
            'manage_employees'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_crm',
            'view_tasks',
            'view_storage',
            'manage_employees'
        ];

        foreach ($permissions as $permission) {
            $p = \Spatie\Permission\Models\Permission::where('name', $permission)->first();
            if ($p) {
                $p->delete();
            }
        }
    }
};
