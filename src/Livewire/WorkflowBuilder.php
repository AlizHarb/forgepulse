<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Livewire;

use AlizHarb\ForgePulse\Enums\StepType;
use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Models\WorkflowStep;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Workflow Builder Component
 *
 * Drag-and-drop workflow designer with real-time updates using Livewire 4.
 */
#[Title('Workflow Builder')]
class WorkflowBuilder extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public Workflow $workflow;

    /** @var array<int, array<string, mixed>> */
    public array $steps = [];

    public ?int $selectedStepId = null;

    public bool $showStepEditor = false;

    public int $canvasZoom = 100;

    public bool $gridSnap = true;

    /**
     * Mount the component.
     */
    public function mount(Workflow $workflow): void
    {
        $this->authorize('update', $workflow);

        $this->workflow = $workflow;
        $this->gridSnap = config('forgepulse.ui.grid_snap', true);
        $this->refreshSteps();
    }

    /**
     * Refresh steps from database.
     */
    #[On('stepUpdated')]
    #[On('stepDeleted')]
    public function refreshSteps(): void
    {
        $this->steps = $this->workflow->steps()
            ->with('children')
            ->get()
            ->map(fn ($step) => [
                'id' => $step->id,
                'name' => $step->name,
                'type' => $step->type->value,
                'type_label' => $step->type->label(),
                'type_color' => $step->type->color(),
                'type_icon' => $step->type->icon(),
                'position' => $step->position,
                'x_position' => $step->x_position ?? 100,
                'y_position' => $step->y_position ?? 100,
                'parent_step_id' => $step->parent_step_id,
                'is_enabled' => $step->is_enabled,
                'has_conditions' => $step->hasConditions(),
            ])
            ->toArray();
    }

    /**
     * Add a new step to the workflow.
     */
    public function addStep(string $type, int $x = 100, int $y = 100): void
    {
        $this->authorize('update', $this->workflow);

        $stepType = StepType::from($type);
        $position = $this->workflow->steps()->max('position') + 1;

        $step = $this->workflow->steps()->create([
            'name' => $stepType->label().' Step',
            'type' => $stepType,
            'configuration' => $stepType->defaultConfiguration(),
            'position' => $position,
            'x_position' => $x,
            'y_position' => $y,
        ]);

        $this->refreshSteps();
        $this->selectStep($step->id);
    }

    /**
     * Update step position on canvas.
     */
    public function updateStepPosition(int $stepId, int $x, int $y): void
    {
        $this->authorize('update', $this->workflow);

        $step = WorkflowStep::find($stepId);

        if ($step && $step->workflow_id === $this->workflow->id) {
            $step->update([
                'x_position' => $x,
                'y_position' => $y,
            ]);

            $this->refreshSteps();
        }
    }

    /**
     * Connect two steps (set parent relationship).
     */
    public function connectSteps(int $parentId, int $childId): void
    {
        $this->authorize('update', $this->workflow);

        $child = WorkflowStep::find($childId);

        if ($child && $child->workflow_id === $this->workflow->id) {
            $child->update(['parent_step_id' => $parentId]);
            $this->refreshSteps();
        }
    }

    /**
     * Disconnect a step from its parent.
     */
    public function disconnectStep(int $stepId): void
    {
        $this->authorize('update', $this->workflow);

        $step = WorkflowStep::find($stepId);

        if ($step && $step->workflow_id === $this->workflow->id) {
            $step->update(['parent_step_id' => null]);
            $this->refreshSteps();
        }
    }

    /**
     * Delete a step.
     */
    public function deleteStep(int $stepId): void
    {
        $this->authorize('update', $this->workflow);

        $step = WorkflowStep::find($stepId);

        if ($step && $step->workflow_id === $this->workflow->id) {
            // Remove parent relationship from children
            $step->children()->update(['parent_step_id' => null]);

            $step->delete();
            $this->refreshSteps();

            if ($this->selectedStepId === $stepId) {
                $this->selectedStepId = null;
                $this->showStepEditor = false;
            }
        }
    }

    /**
     * Select a step for editing.
     */
    public function selectStep(int $stepId): void
    {
        $this->selectedStepId = $stepId;
        $this->showStepEditor = true;
    }

    /**
     * Close the step editor.
     */
    public function closeStepEditor(): void
    {
        $this->showStepEditor = false;
        $this->selectedStepId = null;
    }

    /**
     * Toggle step enabled/disabled.
     */
    public function toggleStepEnabled(int $stepId): void
    {
        $this->authorize('update', $this->workflow);

        $step = WorkflowStep::find($stepId);

        if ($step && $step->workflow_id === $this->workflow->id) {
            $step->update(['is_enabled' => ! $step->is_enabled]);
            $this->refreshSteps();
        }
    }

    /**
     * Zoom in on canvas.
     */
    public function zoomIn(): void
    {
        $this->canvasZoom = min(200, $this->canvasZoom + 10);
    }

    /**
     * Zoom out on canvas.
     */
    public function zoomOut(): void
    {
        $this->canvasZoom = max(50, $this->canvasZoom - 10);
    }

    /**
     * Reset zoom to 100%.
     */
    public function resetZoom(): void
    {
        $this->canvasZoom = 100;
    }

    /**
     * Toggle grid snapping.
     */
    public function toggleGridSnap(): void
    {
        $this->gridSnap = ! $this->gridSnap;
    }

    /**
     * Save workflow configuration.
     */
    public function save(): void
    {
        $this->authorize('update', $this->workflow);

        $this->workflow->update([
            'configuration' => [
                'canvas_zoom' => $this->canvasZoom,
                'grid_snap' => $this->gridSnap,
            ],
        ]);

        $this->dispatch('workflow-saved');
    }

    /**
     * Get available step types.
     *
     * @return array<string, array<string, string>>
     */
    #[Computed]
    public function stepTypes(): array
    {
        return collect(StepType::cases())
            ->mapWithKeys(fn (StepType $type) => [
                $type->value => [
                    'label' => $type->label(),
                    'icon' => $type->icon(),
                    'color' => $type->color(),
                ],
            ])
            ->toArray();
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        /** @var \Illuminate\View\View */
        return view('forgepulse::livewire.workflow-builder');
    }
}
