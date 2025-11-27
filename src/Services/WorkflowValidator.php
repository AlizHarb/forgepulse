<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services;

use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Models\WorkflowStep;

/**
 * Workflow Validator Service
 *
 * Validates workflow structure, detects circular dependencies,
 * and ensures configuration integrity.
 */
class WorkflowValidator
{
    /**
     * Validate a workflow.
     *
     * @param  Workflow  $workflow  The workflow to validate
     * @return bool Whether the workflow is valid
     *
     * @throws \InvalidArgumentException
     */
    public function validate(Workflow $workflow): bool
    {
        $this->validateBasicStructure($workflow);
        $this->validateSteps($workflow);
        $this->detectCircularDependencies($workflow);

        return true;
    }

    /**
     * Validate basic workflow structure.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateBasicStructure(Workflow $workflow): void
    {
        if (empty($workflow->name)) {
            throw new \InvalidArgumentException('Workflow must have a name');
        }

        if ($workflow->steps()->count() === 0) {
            throw new \InvalidArgumentException('Workflow must have at least one step');
        }
    }

    /**
     * Validate workflow steps.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateSteps(Workflow $workflow): void
    {
        $steps = $workflow->steps;
        /** @var array<string, mixed> $configTypes */
        $configTypes = config('forgepulse.step_types', []);
        $stepTypes = array_keys($configTypes);

        foreach ($steps as $step) {
            // Validate step type
            if (! in_array($step->type->value, $stepTypes)) {
                throw new \InvalidArgumentException(
                    "Invalid step type '{$step->type->value}' in step '{$step->name}'"
                );
            }

            // Validate configuration
            if (empty($step->configuration)) {
                throw new \InvalidArgumentException(
                    "Step '{$step->name}' must have configuration"
                );
            }

            // Validate parent step exists
            if ($step->parent_step_id && ! $steps->contains('id', $step->parent_step_id)) {
                throw new \InvalidArgumentException(
                    "Step '{$step->name}' references non-existent parent step"
                );
            }
        }
    }

    /**
     * Detect circular dependencies in workflow steps.
     *
     * @throws \InvalidArgumentException
     */
    protected function detectCircularDependencies(Workflow $workflow): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, \AlizHarb\ForgePulse\Models\WorkflowStep> $steps */
        $steps = $workflow->steps;
        $visited = [];
        $recursionStack = [];

        foreach ($steps->whereNull('parent_step_id') as $rootStep) {
            /** @var \AlizHarb\ForgePulse\Models\WorkflowStep $rootStep */
            if ($this->hasCircularDependency($rootStep, $steps, $visited, $recursionStack)) {
                throw new \InvalidArgumentException(
                    'Circular dependency detected in workflow steps'
                );
            }
        }
    }

    /**
     * Check if a step has circular dependencies.
     *
     * @param  \AlizHarb\ForgePulse\Models\WorkflowStep  $step  Current step
     * @param  \Illuminate\Database\Eloquent\Collection<int, \AlizHarb\ForgePulse\Models\WorkflowStep>  $allSteps  All workflow steps
     * @param  array<int, bool>  $visited  Visited steps
     * @param  array<int, bool>  $recursionStack  Current recursion stack
     * @return bool Whether circular dependency exists
     */
    protected function hasCircularDependency(WorkflowStep $step, $allSteps, array &$visited, array &$recursionStack): bool
    {
        $stepId = $step->id;

        if (isset($recursionStack[$stepId])) {
            return true; // Circular dependency found
        }

        if (isset($visited[$stepId])) {
            return false; // Already processed this branch
        }

        $visited[$stepId] = true;
        $recursionStack[$stepId] = true;

        // Check all child steps
        $children = $allSteps->where('parent_step_id', $stepId);

        foreach ($children as $child) {
            /** @var \AlizHarb\ForgePulse\Models\WorkflowStep $child */
            if ($this->hasCircularDependency($child, $allSteps, $visited, $recursionStack)) {
                return true;
            }
        }

        unset($recursionStack[$stepId]);

        return false;
    }
}
