<?php

namespace AlizHarb\FlowForge\Database\Factories;

use AlizHarb\FlowForge\Models\WorkflowExecution;
use AlizHarb\FlowForge\Models\WorkflowExecutionLog;
use AlizHarb\FlowForge\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AlizHarb\FlowForge\Models\WorkflowExecutionLog>
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
