<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Services\StepHandlers;

use AlizHarb\FlowForge\Models\WorkflowStep;

/**
 * Delay Handler
 *
 * Introduces a delay in workflow execution.
 */
class DelayHandler
{
    /**
     * Handle the step execution.
     *
     * @param  WorkflowStep  $step  The step to execute
     * @param  array<string, mixed>  $context  Execution context
     * @return array<string, mixed> Output data
     */
    public function handle(WorkflowStep $step, array $context): array
    {
        $config = $step->configuration;
        $seconds = $config['seconds'] ?? 0;

        if ($seconds > 0) {
            sleep($seconds);
        }

        return ['delayed_seconds' => $seconds];
    }
}
