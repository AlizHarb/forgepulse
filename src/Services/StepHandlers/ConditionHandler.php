<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services\StepHandlers;

use AlizHarb\ForgePulse\Models\WorkflowStep;

/**
 * Condition Handler
 *
 * Evaluates conditions and routes execution flow.
 */
class ConditionHandler
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
        // Condition steps don't produce output, they just control flow
        // The actual conditional logic is handled by the WorkflowEngine
        return ['condition_evaluated' => true];
    }
}
