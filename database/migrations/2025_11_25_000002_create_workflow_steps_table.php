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
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_step_id')->nullable()->constrained('workflow_steps')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->index();
            $table->json('configuration');
            $table->json('conditions')->nullable();
            $table->integer('position')->default(0);
            $table->integer('x_position')->nullable();
            $table->integer('y_position')->nullable();
            $table->boolean('is_enabled')->default(true)->index();
            $table->timestamps();

            // Performance indexes
            $table->index(['workflow_id', 'position']);
            $table->index(['workflow_id', 'is_enabled']);
            $table->index(['parent_step_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
