<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services;

use AlizHarb\ForgePulse\Enums\LogStatus;
use AlizHarb\ForgePulse\Events\StepExecuted;
use AlizHarb\ForgePulse\Events\WorkflowCompleted;
use AlizHarb\ForgePulse\Events\WorkflowFailed;
use AlizHarb\ForgePulse\Models\WorkflowExecution;
use AlizHarb\ForgePulse\Models\WorkflowExecutionLog;
use AlizHarb\ForgePulse\Models\WorkflowStep;
use Illuminate\Support\Facades\Log;

/**
 * Workflow Engine Service
 *
 * Orchestrates the execution of workflows, managing step sequencing,
 * error handling, and event dispatching.
 */
final readonly class WorkflowEngine
{
    public function __construct(
        private StepExecutor $stepExecutor
    ) {}

    /**
     * Execute a workflow execution.
     *
     * @throws \Exception
     */
    public function execute(WorkflowExecution $execution): void
    {
        try {
            $execution->markAsStarted();

            $workflow = $execution->workflow;
            $context = $execution->context?->getArrayCopy() ?? [];

            // Get root steps (steps without parents)
            $rootSteps = $workflow->steps()
                ->enabled()
                ->roots()
                ->get();

            // Execute steps recursively
            foreach ($rootSteps as $step) {
                // Check if execution is paused
                $freshExecution = $execution->fresh();
                if ($freshExecution && $freshExecution->isPaused()) {
                    $this->logExecution($execution, 'Workflow paused by user');

                    return;
                }

                $context = $this->executeStep($execution, $step, $context);
            }

            $execution->markAsCompleted($context);

            if (config('forgepulse.events.workflow_completed', true)) {
                event(new WorkflowCompleted($execution));
            }

            $this->logExecution($execution, 'Workflow completed successfully');
        } catch (\Exception $e) {
            $execution->markAsFailed($e->getMessage());

            if (config('forgepulse.events.workflow_failed', true)) {
                event(new WorkflowFailed($execution, $e->getMessage()));
            }

            $this->logExecution($execution, 'Workflow failed: '.$e->getMessage(), 'error');
        }
    }

    /**
     * Execute a single step and its children.
     *
     * @param  array<string, mixed>  $context  Current execution context
     * @return array<string, mixed> Updated context
     */
    private function executeStep(WorkflowExecution $execution, WorkflowStep $step, array $context): array
    {
        // Create execution log
        $log = WorkflowExecutionLog::create([
            'workflow_execution_id' => $execution->id,
            'workflow_step_id' => $step->id,
            'status' => LogStatus::PENDING,
        ]);

        try {
            // Check if step should be executed based on conditions
            if ($step->hasConditions() && ! $step->evaluateConditions($context)) {
                $log->markAsSkipped();
                $this->logExecution($execution, "Step '{$step->name}' skipped due to conditions");

                return $context;
            }

            $log->markAsStarted($context);

            // Execute the step
            $output = $this->stepExecutor->execute($step, $context);

            // Merge output into context
            $context = array_merge($context, $output);

            $log->markAsCompleted($output);

            if (config('forgepulse.events.step_executed', true)) {
                event(new StepExecuted($step, $log));
            }

            $this->logExecution($execution, "Step '{$step->name}' executed successfully");

            // Execute child steps
            foreach ($step->children()->enabled()->get() as $childStep) {
                $context = $this->executeStep($execution, $childStep, $context);
            }

            return $context;
        } catch (\AlizHarb\ForgePulse\Exceptions\StepTimeoutException $e) {
            $log->markAsFailed($e->getMessage());

            $this->logExecution(
                $execution,
                "Step '{$step->name}' timed out: ".$e->getMessage(),
                'error'
            );

            throw $e;
        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());

            $this->logExecution(
                $execution,
                "Step '{$step->name}' failed: ".$e->getMessage(),
                'error'
            );

            throw $e;
        }
    }

    /**
     * Log execution information.
     */
    private function logExecution(WorkflowExecution $execution, string $message, string $level = 'info'): void
    {
        if (! config('forgepulse.logging.enabled', true)) {
            return;
        }

        $channel = config('forgepulse.logging.channel', 'stack');

        Log::channel($channel)->$level($message, [
            'workflow_id' => $execution->workflow_id,
            'execution_id' => $execution->id,
        ]);
    }
}
