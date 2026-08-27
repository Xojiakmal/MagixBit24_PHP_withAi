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
            $table->date('start_date')->nullable();
            $table->json('observers')->nullable();
            $table->boolean('available_to_everyone')->default(false);
            $table->foreignId('assigned_to_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->boolean('assigned_to_everyone')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to_department_id']);
            $table->dropColumn([
                'start_date', 'observers', 'available_to_everyone',
                'assigned_to_department_id', 'assigned_to_everyone'
            ]);
        });
    }
};
