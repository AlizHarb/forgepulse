<?php

namespace AlizHarb\ForgePulse\Database\Factories;

use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Models\WorkflowExecution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\AlizHarb\ForgePulse\Models\WorkflowExecution>
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
