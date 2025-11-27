<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services;

/**
 * Conditional Evaluator Service
 *
 * Evaluates conditional expressions for workflow branching logic.
 * Supports complex boolean expressions with variable substitution.
 */
final readonly class ConditionalEvaluator
{
    /**
     * Evaluate conditions against the given context.
     *
     * @param  array<string, mixed>  $conditions  Condition configuration
     * @param  array<string, mixed>  $context  Execution context data
     */
    public function evaluate(array $conditions, array $context): bool
    {
        if (empty($conditions)) {
            return true;
        }

        // Support different condition formats
        if (isset($conditions['operator'])) {
            return $this->evaluateConditionGroup($conditions, $context);
        }

        // Simple key-value conditions
        foreach ($conditions as $key => $expectedValue) {
            $actualValue = data_get($context, $key);

            if ($actualValue !== $expectedValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a condition group with logical operators.
     *
     * @param  array<string, mixed>  $conditionGroup  Condition group configuration
     * @param  array<string, mixed>  $context  Execution context data
     */
    private function evaluateConditionGroup(array $conditionGroup, array $context): bool
    {
        $operator = $conditionGroup['operator'] ?? 'and';
        $rules = $conditionGroup['rules'] ?? [];

        if (empty($rules)) {
            return true;
        }

        $results = [];

        foreach ($rules as $rule) {
            // Nested condition group
            if (isset($rule['rules'])) {
                $results[] = $this->evaluateConditionGroup($rule, $context);

                continue;
            }

            // Single condition rule
            $results[] = $this->evaluateRule($rule, $context);
        }

        return match (strtolower($operator)) {
            'and' => ! in_array(false, $results, true),
            'or' => in_array(true, $results, true),
            'not' => ! in_array(true, $results, true),
            default => throw new \InvalidArgumentException("Unknown operator: {$operator}"),
        };
    }

    /**
     * Evaluate a single condition rule.
     *
     * @param  array<string, mixed>  $rule  Rule configuration
     * @param  array<string, mixed>  $context  Execution context data
     */
    private function evaluateRule(array $rule, array $context): bool
    {
        $field = $rule['field'] ?? null;
        $operator = $rule['operator'] ?? '==';
        $value = $rule['value'] ?? null;

        if (! $field) {
            return false;
        }

        $actualValue = data_get($context, $field);

        return match ($operator) {
            '==' => $actualValue == $value,
            '===' => $actualValue === $value,
            '!=' => $actualValue != $value,
            '!==' => $actualValue !== $value,
            '>' => $actualValue > $value,
            '>=' => $actualValue >= $value,
            '<' => $actualValue < $value,
            '<=' => $actualValue <= $value,
            'in' => is_array($value) && in_array($actualValue, $value, true),
            'not_in' => is_array($value) && ! in_array($actualValue, $value, true),
            'contains' => is_string($actualValue) && str_contains($actualValue, (string) $value),
            'starts_with' => is_string($actualValue) && str_starts_with($actualValue, (string) $value),
            'ends_with' => is_string($actualValue) && str_ends_with($actualValue, (string) $value),
            'is_null' => is_null($actualValue),
            'is_not_null' => ! is_null($actualValue),
            'is_empty' => empty($actualValue),
            'is_not_empty' => ! empty($actualValue),
            default => throw new \InvalidArgumentException("Unknown operator: {$operator}"),
        };
    }
}
