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
        Schema::table('workflow_executions', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->nullable()->after('paused_at')->index();
            $table->json('schedule_config')->nullable()->after('scheduled_at')->comment('Recurring schedule configuration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_executions', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at']);
            $table->dropColumn(['scheduled_at', 'schedule_config']);
        });
    }
};
