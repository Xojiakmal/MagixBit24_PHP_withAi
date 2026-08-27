<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Teams Table
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->timestamps();
        });

        // 2. Add team_id to Users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
        });

        // 3. Folders Table for Storage
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('folders')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->timestamps();
        });

        // 4. Add folder_id to Files
        Schema::table('files', function (Blueprint $table) {
            $table->foreignId('folder_id')->nullable()->constrained('folders')->onDelete('cascade');
        });

        // 5. Contact Groups Table
        Schema::create('contact_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->timestamps();
        });
        
        // Add contact_group_id to contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('contact_group_id')->nullable()->constrained('contact_groups')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['contact_group_id']);
            $table->dropColumn('contact_group_id');
        });
        Schema::dropIfExists('contact_groups');

        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn('folder_id');
        });
        Schema::dropIfExists('folders');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
        Schema::dropIfExists('teams');
    }
};
