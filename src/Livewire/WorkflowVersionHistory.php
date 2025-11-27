<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Livewire;

use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Models\WorkflowVersion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Workflow Version History Component
 *
 * Displays version history with comparison and rollback functionality.
 */
class WorkflowVersionHistory extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public Workflow $workflow;

    public ?int $selectedVersionId = null;

    public ?int $compareVersionId = null;

    public bool $showRollbackConfirm = false;

    public ?int $rollbackVersionId = null;

    /** @var array<string, mixed>|null */
    public ?array $versionDiff = null;

    /**
     * Mount the component.
     */
    public function mount(Workflow $workflow): void
    {
        $this->authorize('view', $workflow);
        $this->workflow = $workflow;
    }

    /**
     * Select a version for viewing.
     */
    public function selectVersion(int $versionId): void
    {
        $this->selectedVersionId = $versionId;
        $this->versionDiff = null;
    }

    /**
     * Compare two versions.
     */
    public function compareVersions(int $versionId1, int $versionId2): void
    {
        $version1 = WorkflowVersion::find($versionId1);
        $version2 = WorkflowVersion::find($versionId2);

        if ($version1 && $version2) {
            $this->versionDiff = $version1->compare($version2);
            $this->selectedVersionId = $versionId1;
            $this->compareVersionId = $versionId2;
        }
    }

    /**
     * Show rollback confirmation dialog.
     */
    public function confirmRollback(int $versionId): void
    {
        $this->rollbackVersionId = $versionId;
        $this->showRollbackConfirm = true;
    }

    /**
     * Cancel rollback.
     */
    public function cancelRollback(): void
    {
        $this->showRollbackConfirm = false;
        $this->rollbackVersionId = null;
    }

    /**
     * Execute rollback to selected version.
     */
    public function executeRollback(): void
    {
        $this->authorize('update', $this->workflow);

        if ($this->rollbackVersionId) {
            try {
                $this->workflow->restoreVersion($this->rollbackVersionId);
                $this->workflow->refresh();

                $this->dispatch('workflow-restored', versionId: $this->rollbackVersionId);
                $this->dispatch('close-version-history');
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Workflow restored to version '.$this->rollbackVersionId,
                ]);

                $this->showRollbackConfirm = false;
                $this->rollbackVersionId = null;
                $this->selectedVersionId = null;
                $this->versionDiff = null;
            } catch (\Exception $e) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Failed to restore version: '.$e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Close the version history modal.
     */
    #[On('close-version-history')]
    public function close(): void
    {
        $this->dispatch('close-modal');
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        $versions = $this->workflow->versions()->with('creator')->get();

        /** @phpstan-ignore argument.type */
        return view('forgepulse::livewire.workflow-version-history', [
            'versions' => $versions,
        ]);
    }
}
