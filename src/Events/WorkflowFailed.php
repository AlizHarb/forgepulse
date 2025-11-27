<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Events;

use AlizHarb\ForgePulse\Models\WorkflowExecution;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * WorkflowFailed Event
 *
 * Dispatched when a workflow execution fails. This can occur due to step failures,
 * exceptions, or validation errors during execution.
 *
 * @author Ali Harb <harbzali@gmail.com>
 */
final class WorkflowFailed
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  WorkflowExecution  $execution  The failed workflow execution
     * @param  string  $error  Error message describing the failure
     */
    public function __construct(
        public readonly WorkflowExecution $execution,
        public readonly string $error
    ) {}
}
