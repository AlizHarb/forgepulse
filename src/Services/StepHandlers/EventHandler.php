<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services\StepHandlers;

use AlizHarb\ForgePulse\Models\WorkflowStep;

/**
 * Event Handler
 *
 * Dispatches Laravel events during workflow execution.
 */
class EventHandler
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
        $eventClass = $config['event_class'] ?? null;
        $parameters = $config['parameters'] ?? [];

        if (! $eventClass || ! class_exists($eventClass)) {
            throw new \RuntimeException("Event class not found: {$eventClass}");
        }

        $event = new $eventClass(...array_values($parameters));

        event($event);

        return ['event_dispatched' => true];
    }
}
