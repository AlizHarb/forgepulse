<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Livewire;

use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Services\TemplateManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Workflow Template Manager Component
 *
 * Interface for managing workflow templates using Livewire 4.
 */
#[Title('Template Manager')]
class WorkflowTemplateManager extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public ?Workflow $currentWorkflow = null;

    #[Validate('required|string|max:255')]
    public string $templateName = '';

    public bool $showSaveModal = false;

    public bool $showLoadModal = false;

    /**
     * Mount the component.
     */
    public function mount(?Workflow $workflow = null): void
    {
        $this->currentWorkflow = $workflow;
    }

    /**
     * Get available templates.
     *
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function templates(): array
    {
        return Workflow::templates()
            ->with('steps')
            ->get()
            ->map(fn ($template) => [
                'id' => $template->id,
                'name' => $template->name,
                'description' => $template->description,
                'version' => $template->version,
                'steps_count' => $template->steps->count(),
                'created_at' => $template->created_at->format('Y-m-d H:i:s'),
            ])
            ->toArray();
    }

    /**
     * Save current workflow as template.
     */
    public function saveAsTemplate(): void
    {
        if (! $this->currentWorkflow) {
            return;
        }

        $this->authorize('manageTemplates', Workflow::class);

        $this->validate();

        $this->currentWorkflow->saveAsTemplate($this->templateName);

        unset($this->templates); // Clear computed cache

        $this->showSaveModal = false;
        $this->templateName = '';

        $this->dispatch('template-saved');
    }

    /**
     * Load a template and create a new workflow.
     */
    public function loadTemplate(int $templateId): void
    {
        $this->authorize('create', Workflow::class);

        $template = Workflow::findOrFail($templateId);

        if (! $template->is_template) {
            return;
        }

        $workflow = $template->instantiateFromTemplate();

        $this->redirect(route('workflows.edit', $workflow));
    }

    /**
     * Export template to file.
     */
    public function exportTemplate(int $templateId): void
    {
        $this->authorize('manageTemplates', Workflow::class);

        $template = Workflow::findOrFail($templateId);

        $templateManager = app(TemplateManager::class);
        $path = $templateManager->export($template);

        $this->dispatch('template-exported', path: $path);
    }

    /**
     * Delete a template.
     */
    public function deleteTemplate(int $templateId): void
    {
        $this->authorize('manageTemplates', Workflow::class);

        $template = Workflow::findOrFail($templateId);

        if ($template->is_template) {
            $template->delete();
            unset($this->templates); // Clear computed cache
        }
    }

    /**
     * Open save modal.
     */
    public function openSaveModal(): void
    {
        $this->showSaveModal = true;
    }

    /**
     * Close save modal.
     */
    public function closeSaveModal(): void
    {
        $this->showSaveModal = false;
        $this->templateName = '';
        $this->resetValidation();
    }

    /**
     * Open load modal.
     */
    public function openLoadModal(): void
    {
        $this->showLoadModal = true;
    }

    /**
     * Close load modal.
     */
    public function closeLoadModal(): void
    {
        $this->showLoadModal = false;
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        /** @var \Illuminate\View\View */
        return view('forgepulse::livewire.workflow-template-manager');
    }
}
