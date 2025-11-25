<div class="flowforge-modal" x-show="$wire.showStepEditor" x-cloak>
    <div class="flowforge-modal-backdrop" @click="$wire.closeStepEditor()"></div>
    
    <div class="flowforge-modal-content">
        <div class="flowforge-modal-header">
            <h3 class="flowforge-modal-title">{{ __('flowforge::flowforge.step.edit') }}</h3>
            <button @click="$wire.closeStepEditor()" class="flowforge-modal-close">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flowforge-modal-body">
            <form wire:submit.prevent="save">
                {{-- Step Name --}}
                <div class="flowforge-form-group">
                    <label for="step-name" class="flowforge-label">{{ __('flowforge::flowforge.step.name') }}</label>
                    <input type="text" id="step-name" wire:model="name" class="flowforge-input" required>
                    @error('name') <span class="flowforge-error">{{ $message }}</span> @enderror
                </div>

                {{-- Step Description --}}
                <div class="flowforge-form-group">
                    <label for="step-description" class="flowforge-label">{{ __('flowforge::flowforge.workflow.description') }}</label>
                    <textarea id="step-description" wire:model="description" class="flowforge-input" rows="3"></textarea>
                    @error('description') <span class="flowforge-error">{{ $message }}</span> @enderror
                </div>

                {{-- Step Type (Read-only) --}}
                <div class="flowforge-form-group">
                    <label class="flowforge-label">{{ __('flowforge::flowforge.step.type') }}</label>
                    <div class="flowforge-input-readonly">
                        {{ __('flowforge::flowforge.step_types.' . $type) }}
                    </div>
                </div>

            {{-- Configuration based on step type --}}
            @if($type === 'action')
                <div class="flowforge-form-group">
                    <label class="flowforge-label">Action Class</label>
                    <input type="text" wire:model="configuration.action_class" class="flowforge-input" placeholder="App\Actions\MyAction">
                </div>
            @elseif($type === 'notification')
                <div class="flowforge-form-group">
                    <label class="flowforge-label">Notification Class</label>
                    <input type="text" wire:model="configuration.notification_class" class="flowforge-input" placeholder="App\Notifications\MyNotification">
                </div>
            @elseif($type === 'webhook')
                <div class="flowforge-form-group">
                    <label class="flowforge-label">Webhook URL</label>
                    <input type="url" wire:model="configuration.url" class="flowforge-input" placeholder="https://example.com/webhook">
                </div>
                <div class="flowforge-form-group">
                    <label class="flowforge-label">HTTP Method</label>
                    <select wire:model="configuration.method" class="flowforge-select">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="PATCH">PATCH</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>
            @elseif($type === 'delay')
                <div class="flowforge-form-group">
                    <label class="flowforge-label">Delay (seconds)</label>
                    <input type="number" wire:model="configuration.seconds" class="flowforge-input" min="0">
                </div>
            @endif

            {{-- Conditions --}}
            <div class="flowforge-form-group">
                <label class="flowforge-label">Conditions (Optional)</label>
                <div class="flowforge-conditions">
                    @if(isset($conditions['rules']) && is_array($conditions['rules']))
                        @foreach($conditions['rules'] as $index => $rule)
                            <div class="flowforge-condition-rule">
                                <input type="text" wire:model="conditions.rules.{{ $index }}.field" class="flowforge-input flowforge-input-sm" placeholder="Field">
                                <select wire:model="conditions.rules.{{ $index }}.operator" class="flowforge-select flowforge-select-sm">
                                    <option value="==">Equals</option>
                                    <option value="!=">Not Equals</option>
                                    <option value=">">Greater Than</option>
                                    <option value="<">Less Than</option>
                                    <option value="contains">Contains</option>
                                </select>
                                <input type="text" wire:model="conditions.rules.{{ $index }}.value" class="flowforge-input flowforge-input-sm" placeholder="Value">
                                <button wire:click="removeCondition({{ $index }})" class="flowforge-btn flowforge-btn-sm flowforge-btn-danger">Remove</button>
                            </div>
                        @endforeach
                    @endif
                    <button wire:click="addCondition" class="flowforge-btn flowforge-btn-sm">Add Condition</button>
                </div>
            </div>

            <div class="flowforge-form-group">
                <label class="flowforge-checkbox">
                    <input type="checkbox" wire:model="isEnabled">
                    <span>Step Enabled</span>
                </label>
            </div>
        </div>

        <div class="flowforge-modal-footer">
            <button @click="open = false" class="flowforge-btn">Cancel</button>
            <button wire:click="save" class="flowforge-btn flowforge-btn-primary">Save Changes</button>
        </div>
    </div>
</div>
