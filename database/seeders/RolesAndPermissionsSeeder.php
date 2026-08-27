<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        $managerRole = \Spatie\Permission\Models\Role::create(['name' => 'Menejer']);
        $employeeRole = \Spatie\Permission\Models\Role::create(['name' => 'Xodim']);
    }
}
