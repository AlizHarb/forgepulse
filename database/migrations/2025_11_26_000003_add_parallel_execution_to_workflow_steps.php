<?php

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
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->string('execution_mode')->default('sequential')->after('timeout')->comment('sequential or parallel');
            $table->string('parallel_group')->nullable()->after('execution_mode')->comment('Group identifier for parallel steps');
            $table->index(['workflow_id', 'parallel_group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {
            $table->dropIndex(['workflow_id', 'parallel_group']);
            $table->dropColumn(['execution_mode', 'parallel_group']);
        });
    }
};
