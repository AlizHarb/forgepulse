<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workflow_execution_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_execution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workflow_step_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->json('input')->nullable();
            $table->json('output')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('execution_time_ms')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index(['workflow_execution_id', 'status']);
            $table->index(['workflow_step_id']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_execution_logs');
    }
};
