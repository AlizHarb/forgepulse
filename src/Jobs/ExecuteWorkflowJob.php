<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Jobs;

use AlizHarb\FlowForge\Models\WorkflowExecution;
use AlizHarb\FlowForge\Services\WorkflowEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * ExecuteWorkflowJob
 *
 * Queued job for asynchronous workflow execution. Implements ShouldBeUnique to
 * prevent duplicate executions of the same workflow execution instance.
 *
 * The job delegates to the WorkflowEngine service for actual execution logic,
 * ensuring separation of concerns and testability.
 *
 * @author Ali Harb <harbzali@gmail.com>
 */
final class ExecuteWorkflowJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout;

    /**
     * Create a new job instance.
     *
     * @param  WorkflowExecution  $execution  The workflow execution to process
     */
    public function __construct(
        public readonly WorkflowExecution $execution
    ) {
        $this->tries = config('flowforge.execution.max_retries', 3);
        $this->timeout = config('flowforge.execution.timeout', 3600);
        $this->onQueue(config('flowforge.execution.queue', 'default'));
    }

    /**
     * Execute the job.
     *
     * @param  WorkflowEngine  $engine  The workflow engine service
     */
    public function handle(WorkflowEngine $engine): void
    {
        $engine->execute($this->execution);
    }

    /**
     * Get the unique ID for the job.
     *
     * Ensures only one job per execution can be queued at a time.
     *
     * @return string Unique identifier for this job
     */
    public function uniqueId(): string
    {
        return "workflow-execution-{$this->execution->id}";
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string> Job tags for monitoring and filtering
     */
    public function tags(): array
    {
        return [
            'workflow:'.$this->execution->workflow_id,
            'execution:'.$this->execution->id,
        ];
    }
}
