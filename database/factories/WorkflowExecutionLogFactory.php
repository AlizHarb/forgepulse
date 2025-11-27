<?php

namespace AlizHarb\ForgePulse\Database\Factories;

use AlizHarb\ForgePulse\Models\WorkflowExecution;
use AlizHarb\ForgePulse\Models\WorkflowExecutionLog;
use AlizHarb\ForgePulse\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AlizHarb\ForgePulse\Models\WorkflowExecutionLog>
 */
class WorkflowExecutionLogFactory extends Factory
{
    protected $model = WorkflowExecutionLog::class;

    public function definition(): array
    {
        return [
            'workflow_execution_id' => WorkflowExecution::factory(),
            'workflow_step_id' => WorkflowStep::factory(),
            'status' => 'completed',
            'output' => [],
            'started_at' => now(),
            'completed_at' => now(),
        ];
    }
}
