<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Livewire;

use AlizHarb\FlowForge\Models\WorkflowStep;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Workflow Step Editor Component
 *
 * Modal editor for configuring individual workflow steps using Livewire 4.
 */
#[Title('Step Editor')]
class WorkflowStepEditor extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public ?WorkflowStep $step = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Locked]
    public string $type = '';

    /** @var array<string, mixed> */
    #[Validate('required|array')]
    public array $configuration = [];

    /** @var array<string, mixed> */
    public array $conditions = [];

    public bool $isEnabled = true;

    /**
     * Mount the component.
     */
    public function mount(?int $stepId = null): void
    {
        if ($stepId) {
            $this->step = WorkflowStep::findOrFail($stepId);
            $this->authorize('update', $this->step->workflow);

            $this->name = $this->step->name;
            $this->description = $this->step->description ?? '';
            $this->type = $this->step->type->value;
            $this->configuration = $this->step->configuration->getArrayCopy();
            $this->conditions = $this->step->conditions?->getArrayCopy() ?? [];
            $this->isEnabled = $this->step->is_enabled;
        }
    }

    /**
     * Save the step configuration.
     */
    public function save(): void
    {
        if (! $this->step) {
            return;
        }

        $this->authorize('update', $this->step->workflow);

        $this->validate();

        $this->step->update([
            'name' => $this->name,
            'description' => $this->description,
            'configuration' => $this->configuration,
            'conditions' => $this->conditions,
            'is_enabled' => $this->isEnabled,
        ]);

        $this->dispatch('stepUpdated');
        $this->dispatch('close-modal');
    }

    /**
     * Add a condition rule.
     */
    public function addCondition(): void
    {
        if (! isset($this->conditions['rules'])) {
            $this->conditions['rules'] = [];
        }

        $this->conditions['rules'][] = [
            'field' => '',
            'operator' => '==',
            'value' => '',
        ];
    }

    /**
     * Remove a condition rule.
     */
    public function removeCondition(int $index): void
    {
        unset($this->conditions['rules'][$index]);
        $this->conditions['rules'] = array_values($this->conditions['rules']);
    }

    /**
     * Update configuration field.
     */
    public function updateConfiguration(string $key, mixed $value): void
    {
        data_set($this->configuration, $key, $value);
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        /** @var \Illuminate\View\View */
        /** @phpstan-ignore argument.type */
        return view('flowforge::livewire.workflow-step-editor');
    }
}
