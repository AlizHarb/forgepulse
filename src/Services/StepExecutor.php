<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Services;

use AlizHarb\FlowForge\Models\WorkflowStep;

/**
 * Step Executor Service
 *
 * Executes individual workflow steps by delegating to appropriate handlers.
 */
final readonly class StepExecutor
{
    /**
     * Execute a workflow step.
     *
     * @param  array<string, mixed>  $context  Current execution context
     * @return array<string, mixed> Output data from step execution
     *
     * @throws \RuntimeException
     */
    public function execute(WorkflowStep $step, array $context): array
    {
        $handlerClass = $step->getHandlerClass();

        if (! class_exists($handlerClass)) {
            throw new \RuntimeException("Handler class not found: {$handlerClass}");
        }

        $handler = app($handlerClass);

        if (! method_exists($handler, 'handle')) {
            throw new \RuntimeException("Handler must implement handle() method: {$handlerClass}");
        }

        $timeout = $step->getTimeout();
        $usePcntl = $timeout && function_exists('pcntl_alarm') && function_exists('pcntl_signal');

        if ($usePcntl) {
            pcntl_async_signals(true);
            pcntl_signal(SIGALRM, function () use ($step, $timeout) {
                throw new \AlizHarb\FlowForge\Exceptions\StepTimeoutException($step->name, $timeout);
            });
            pcntl_alarm($timeout);
        }

        try {
            /** @phpstan-ignore-next-line */
            return $handler->handle($step, $context);
        } finally {
            if ($usePcntl) {
                pcntl_alarm(0);
                pcntl_signal(SIGALRM, SIG_DFL);
            }
        }
    }
}
