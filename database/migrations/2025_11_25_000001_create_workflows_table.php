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
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft')->index();
            $table->json('configuration')->nullable();
            $table->boolean('is_template')->default(false)->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Only add foreign key constraint if teams are enabled and table exists
            if (config('flowforge.teams.enabled', false) && Schema::hasTable('teams')) {
                $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            } else {
                $table->unsignedBigInteger('team_id')->nullable();
            }
            $table->string('version')->default('1.0.0');
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['user_id', 'status']);
            $table->index(['team_id', 'status']);
            $table->index(['is_template', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
