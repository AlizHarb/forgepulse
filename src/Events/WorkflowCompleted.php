<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Events;

use AlizHarb\ForgePulse\Models\WorkflowExecution;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * WorkflowCompleted Event
 *
 * Dispatched when a workflow execution completes successfully. All steps have
 * been executed and the workflow has reached a completed state.
 *
 * @author Ali Harb <harbzali@gmail.com>
 */
final class WorkflowCompleted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  WorkflowExecution  $execution  The completed workflow execution
     */
    public function __construct(
        public readonly WorkflowExecution $execution
    ) {}
}
