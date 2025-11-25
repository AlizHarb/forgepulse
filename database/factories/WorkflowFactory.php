<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Database\Factories;

use AlizHarb\FlowForge\Models\Workflow;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowFactory extends Factory
{
    protected $model = Workflow::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'status' => 'draft',
            'configuration' => [],
            'is_template' => false,
            'version' => '1.0.0',
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function template(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_template' => true,
            'status' => 'active',
        ]);
    }
}
