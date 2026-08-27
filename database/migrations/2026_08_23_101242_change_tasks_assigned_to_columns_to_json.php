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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['assigned_to_department_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            // Because SQLite and some Postgres versions have trouble changing foreign key columns to JSON directly,
            // we will drop them and recreate them as json nullable.
            $table->dropColumn('assigned_to');
            $table->dropColumn('assigned_to_department_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->json('assigned_to')->nullable();
            $table->json('assigned_to_department_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('assigned_to');
            $table->dropColumn('assigned_to_department_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_to_department_id')->nullable()->constrained('departments')->onDelete('set null');
        });
    }
};
