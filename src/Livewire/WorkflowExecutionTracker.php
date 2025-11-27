<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Livewire;

use AlizHarb\ForgePulse\Models\WorkflowExecution;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Workflow Execution Tracker Component
 *
 * Real-time monitoring of workflow execution with live updates using Livewire 4.
 */
#[Title('Execution Tracker')]
class WorkflowExecutionTracker extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public WorkflowExecution $execution;

    public int $refreshInterval = 2; // seconds

    /**
     * Mount the component.
     */
    public function mount(WorkflowExecution $execution): void
    {
        $this->authorize('view', $execution->workflow);

        $this->execution = $execution;
    }

    /**
     * Refresh execution data.
     */
    #[On('refreshExecution')]
    public function refreshExecution(): void
    {
        $this->execution->refresh();
    }

    /**
     * Get execution logs.
     *
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function logs(): array
    {
        return $this->execution->logs()
            ->with('step')
            ->get()
            ->map(fn ($log) => [
                'id' => $log->id,
                'step_name' => $log->step->name,
                'step_type' => $log->step->type->value,
                'step_type_label' => $log->step->type->label(),
                'status' => $log->status->value,
                'status_label' => $log->status->label(),
                'status_color' => $log->status->color(),
                'status_icon' => $log->status->icon(),
                'started_at' => $log->started_at?->format('Y-m-d H:i:s'),
                'completed_at' => $log->completed_at?->format('Y-m-d H:i:s'),
                'execution_time_ms' => $log->execution_time_ms,
                'error_message' => $log->error_message,
            ])
            ->toArray();
    }

    /**
     * Get polling interval based on execution status.
     */
    #[Computed]
    public function pollingInterval(): ?int
    {
        if ($this->execution->isInProgress()) {
            return $this->refreshInterval * 1000; // Convert to milliseconds
        }

        return null; // Stop polling when finished
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        /** @var \Illuminate\View\View */
        /** @phpstan-ignore argument.type */
        return view('forgepulse::livewire.workflow-execution-tracker');
    }
}
