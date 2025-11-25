<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Database\Factories;

use AlizHarb\FlowForge\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowStepFactory extends Factory
{
    protected $model = WorkflowStep::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'type' => 'action',
            'configuration' => ['action_class' => 'TestAction'],
            'conditions' => null,
            'position' => 1,
            'x_position' => 100,
            'y_position' => 100,
            'is_enabled' => true,
        ];
    }

    public function withConditions(): self
    {
        return $this->state(fn (array $attributes) => [
            'conditions' => [
                'operator' => 'and',
                'rules' => [
                    ['field' => 'test', 'operator' => '==', 'value' => 'value'],
                ],
            ],
        ]);
    }
}
