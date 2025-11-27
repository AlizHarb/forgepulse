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
        Schema::create('workflow_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->cascadeOnDelete();
            $table->integer('version_number')->default(1);
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('configuration')->nullable();
            $table->json('steps_snapshot'); // Complete snapshot of workflow steps
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at');
            $table->timestamp('restored_at')->nullable();

            // Performance indexes
            $table->index(['workflow_id', 'version_number']);
            $table->index(['workflow_id', 'created_at']);
            $table->unique(['workflow_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_versions');
    }
};
