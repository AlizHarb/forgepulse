<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Services\StepHandlers;

use AlizHarb\FlowForge\Models\WorkflowStep;

/**
 * Job Handler
 *
 * Dispatches Laravel jobs during workflow execution.
 */
class JobHandler
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
        /** @var array<string, mixed> $config */
        $config = $step->configuration;
        $jobClass = $config['job_class'] ?? null;
        $parameters = $config['parameters'] ?? [];
        $queue = $config['queue'] ?? null;
        $delay = $config['delay'] ?? null;

        if (! $jobClass || ! class_exists($jobClass)) {
            throw new \RuntimeException("Job class not found: {$jobClass}");
        }

        $job = new $jobClass(...array_values($parameters));

        if ($queue && method_exists($job, 'onQueue')) {
            $job->onQueue($queue);
        }

        if ($delay) {
            dispatch($job)->delay($delay);
        } else {
            dispatch($job);
        }

        return ['job_dispatched' => true];
    }
}
