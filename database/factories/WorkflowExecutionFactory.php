<?php

namespace AlizHarb\FlowForge\Database\Factories;

use AlizHarb\FlowForge\Models\Workflow;
use AlizHarb\FlowForge\Models\WorkflowExecution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AlizHarb\FlowForge\Models\WorkflowExecution>
 */
class WorkflowExecutionFactory extends Factory
{
    protected $model = WorkflowExecution::class;

    public function definition(): array
    {
        return [
            'workflow_id' => Workflow::factory(),
            'status' => 'pending',
            'context' => [],
            'started_at' => now(),
        ];
    }
}
