<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services\StepHandlers;

use AlizHarb\ForgePulse\Models\WorkflowStep;

/**
 * Action Handler
 *
 * Executes custom actions defined in step configuration.
 */
class ActionHandler
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
        $actionClass = $config['action_class'] ?? null;

        if (! $actionClass || ! class_exists($actionClass)) {
            throw new \RuntimeException("Action class not found: {$actionClass}");
        }

        $action = app($actionClass);

        if (! method_exists($action, 'execute')) {
            throw new \RuntimeException("Action must implement execute() method: {$actionClass}");
        }

        $parameters = $config['parameters'] ?? [];

        // Substitute context variables in parameters
        $parameters = $this->substituteVariables($parameters, $context);

        /** @phpstan-ignore-next-line */
        return $action->execute($parameters, $context);
    }

    /**
     * Substitute context variables in parameters.
     *
     * @param  mixed  $value  Value to process
     * @param  array<string, mixed>  $context  Execution context
     * @return mixed Processed value
     */
    protected function substituteVariables($value, array $context)
    {
        if (is_string($value) && preg_match('/^\{\{(.+)\}\}$/', $value, $matches)) {
            return data_get($context, trim($matches[1]));
        }

        if (is_array($value)) {
            return array_map(fn ($v) => $this->substituteVariables($v, $context), $value);
        }

        return $value;
    }
}
