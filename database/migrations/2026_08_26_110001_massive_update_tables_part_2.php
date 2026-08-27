<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Deals Multi-Assignment JSONs
        Schema::table('deals', function (Blueprint $table) {
            // Drop old assigned_to if it exists
            if (Schema::hasColumn('deals', 'assigned_to')) {
                $table->dropForeign(['assigned_to']);
                $table->dropColumn('assigned_to');
            }
            
            $table->json('assigned_users')->nullable();
            $table->json('assigned_roles')->nullable();
            $table->json('assigned_teams')->nullable();
        });

        // 2. Activities old/new values
        Schema::table('activities', function (Blueprint $table) {
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['old_values', 'new_values']);
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn(['assigned_users', 'assigned_roles', 'assigned_teams']);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
