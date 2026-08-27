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
        Schema::table('deals', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->string('deal_type')->nullable();
            $table->string('source')->nullable();
            $table->text('source_info')->nullable();
            $table->json('observers')->nullable();
            $table->text('description')->nullable();
            $table->boolean('available_to_everyone')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn([
                'start_date', 'end_date', 'company_id', 
                'deal_type', 'source', 'source_info', 'observers', 
                'description', 'available_to_everyone'
            ]);
        });
    }
};
