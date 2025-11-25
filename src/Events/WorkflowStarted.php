<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Events;

use AlizHarb\FlowForge\Models\WorkflowExecution;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * WorkflowStarted Event
 *
 * Dispatched when a workflow execution begins. This event is fired immediately
 * after the execution record is created, before any steps are executed.
 *
 * @author Ali Harb <harbzali@gmail.com>
 */
final class WorkflowStarted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  WorkflowExecution  $execution  The workflow execution that started
     */
    public function __construct(
        public readonly WorkflowExecution $execution
    ) {}
}
