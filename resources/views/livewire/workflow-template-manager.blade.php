<div class="forgepulse-template-manager">
    <div class="forgepulse-template-header">
        <h3 class="forgepulse-template-title">{{ __('forgepulse::forgepulse.template.title') }}</h3>
        @if($currentWorkflow)
            <button wire:click="openSaveModal" class="forgepulse-btn forgepulse-btn-primary">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                {{ __('forgepulse::forgepulse.template.save_as') }}
            </button>
        @endif
    </div>

    <div class="forgepulse-templates-grid">
        @forelse($this->templates as $template)
            <div class="forgepulse-template-card">
                <div class="forgepulse-template-header">
                    <h4 class="forgepulse-template-name">{{ $template['name'] }}</h4>
                    <span class="forgepulse-template-version">v{{ $template['version'] }}</span>
                </div>
                <p class="forgepulse-template-description">{{ $template['description'] ?? 'No description' }}</p>
                <div class="forgepulse-template-meta">
                    <span class="forgepulse-template-steps">{{ $template['steps_count'] }} steps</span>
                    <span class="forgepulse-template-date">{{ $template['created_at'] }}</span>
                </div>
                <div class="forgepulse-template-actions">
                    <button wire:click="loadTemplate({{ $template['id'] }})" class="forgepulse-btn forgepulse-btn-sm">
                        Use Template
                    </button>
                    <button wire:click="exportTemplate({{ $template['id'] }})" class="forgepulse-btn forgepulse-btn-sm">
                        Export
                    </button>
                    <button wire:click="deleteTemplate({{ $template['id'] }})" class="forgepulse-btn forgepulse-btn-sm forgepulse-btn-danger">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="forgepulse-empty-state">
                <p>No templates available yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Save Template Modal --}}
    @if($showSaveModal)
        <div class="forgepulse-modal" x-data="{ open: @entangle('showSaveModal') }" x-show="open">
            <div class="forgepulse-modal-backdrop" @click="open = false"></div>
            <div class="forgepulse-modal-content">
                <div class="forgepulse-modal-header">
                    <h3 class="forgepulse-modal-title">Save as Template</h3>
                    <button @click="open = false" class="forgepulse-modal-close">×</button>
                </div>
                <div class="forgepulse-modal-body">
                    <div class="forgepulse-form-group">
                        <label class="forgepulse-label">Template Name</label>
                        <input type="text" wire:model="templateName" class="forgepulse-input" placeholder="Enter template name">
                        @error('templateName') <span class="forgepulse-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="forgepulse-modal-footer">
                    <button wire:click="closeSaveModal" class="forgepulse-btn">Cancel</button>
                    <button wire:click="saveAsTemplate" class="forgepulse-btn forgepulse-btn-primary">Save Template</button>
                </div>
            </div>
        </div>
    @endif
</div>
