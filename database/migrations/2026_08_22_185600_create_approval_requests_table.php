<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->string('action'); // e.g., 'delete_company', 'demote_collaborator'
            $table->json('payload')->nullable(); // extra data if needed
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete(); // whoever approved/rejected it
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
