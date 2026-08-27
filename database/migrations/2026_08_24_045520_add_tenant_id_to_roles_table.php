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
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded.');
        }

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            
            // Drop old unique index. In standard spatie, it's roles_name_guard_name_unique
            $table->dropUnique('roles_name_guard_name_unique');

            // Add new unique index incorporating tenant_id
            $table->unique(['tenant_id', 'name', 'guard_name'], 'roles_tenant_name_guard_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropUnique('roles_tenant_name_guard_unique');
            $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
            $table->dropColumn('tenant_id');
        });
    }
};
