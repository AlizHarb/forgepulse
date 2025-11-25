<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Events;

use AlizHarb\FlowForge\Models\WorkflowExecutionLog;
use AlizHarb\FlowForge\Models\WorkflowStep;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * StepExecuted Event
 *
 * Dispatched after each individual step execution within a workflow. This event
 * provides granular visibility into workflow progress and can be used for logging,
 * monitoring, or triggering side effects based on step completion.
 *
 * @author Ali Harb <harbzali@gmail.com>
 */
final class StepExecuted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  WorkflowStep  $step  The workflow step that was executed
     * @param  WorkflowExecutionLog  $log  The execution log entry for this step
     */
    public function __construct(
        public readonly WorkflowStep $step,
        public readonly WorkflowExecutionLog $log
    ) {}
}
