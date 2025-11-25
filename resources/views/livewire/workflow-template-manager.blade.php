<div class="flowforge-template-manager">
    <div class="flowforge-template-header">
        <h3 class="flowforge-template-title">{{ __('flowforge::flowforge.template.title') }}</h3>
        @if($currentWorkflow)
            <button wire:click="openSaveModal" class="flowforge-btn flowforge-btn-primary">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                {{ __('flowforge::flowforge.template.save_as') }}
            </button>
        @endif
    </div>

    <div class="flowforge-templates-grid">
        @forelse($this->templates as $template)
            <div class="flowforge-template-card">
                <div class="flowforge-template-header">
                    <h4 class="flowforge-template-name">{{ $template['name'] }}</h4>
                    <span class="flowforge-template-version">v{{ $template['version'] }}</span>
                </div>
                <p class="flowforge-template-description">{{ $template['description'] ?? 'No description' }}</p>
                <div class="flowforge-template-meta">
                    <span class="flowforge-template-steps">{{ $template['steps_count'] }} steps</span>
                    <span class="flowforge-template-date">{{ $template['created_at'] }}</span>
                </div>
                <div class="flowforge-template-actions">
                    <button wire:click="loadTemplate({{ $template['id'] }})" class="flowforge-btn flowforge-btn-sm">
                        Use Template
                    </button>
                    <button wire:click="exportTemplate({{ $template['id'] }})" class="flowforge-btn flowforge-btn-sm">
                        Export
                    </button>
                    <button wire:click="deleteTemplate({{ $template['id'] }})" class="flowforge-btn flowforge-btn-sm flowforge-btn-danger">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="flowforge-empty-state">
                <p>No templates available yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Save Template Modal --}}
    @if($showSaveModal)
        <div class="flowforge-modal" x-data="{ open: @entangle('showSaveModal') }" x-show="open">
            <div class="flowforge-modal-backdrop" @click="open = false"></div>
            <div class="flowforge-modal-content">
                <div class="flowforge-modal-header">
                    <h3 class="flowforge-modal-title">Save as Template</h3>
                    <button @click="open = false" class="flowforge-modal-close">×</button>
                </div>
                <div class="flowforge-modal-body">
                    <div class="flowforge-form-group">
                        <label class="flowforge-label">Template Name</label>
                        <input type="text" wire:model="templateName" class="flowforge-input" placeholder="Enter template name">
                        @error('templateName') <span class="flowforge-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flowforge-modal-footer">
                    <button wire:click="closeSaveModal" class="flowforge-btn">Cancel</button>
                    <button wire:click="saveAsTemplate" class="flowforge-btn flowforge-btn-primary">Save Template</button>
                </div>
            </div>
        </div>
    @endif
</div>
