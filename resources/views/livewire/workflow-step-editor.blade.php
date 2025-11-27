<div class="forgepulse-modal">
    <div class="forgepulse-modal-backdrop" @click="$dispatch('close-modal')"></div>
    
    <div class="forgepulse-modal-content" @click.stop>
        <div class="forgepulse-modal-header">
            <h3 class="forgepulse-modal-title">{{ __('forgepulse::forgepulse.step.edit') }}</h3>
            <button @click="$dispatch('close-modal')" class="forgepulse-modal-close">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="forgepulse-modal-body">
            <form wire:submit.prevent="save">
                {{-- Step Name --}}
                <div class="forgepulse-form-group">
                    <label for="step-name" class="forgepulse-label">{{ __('forgepulse::forgepulse.step.name') }}</label>
                    <input type="text" id="step-name" wire:model="name" class="forgepulse-input" required>
                    @error('name') <span class="forgepulse-error">{{ $message }}</span> @enderror
                </div>

                {{-- Step Description --}}
                <div class="forgepulse-form-group">
                    <label for="step-description" class="forgepulse-label">{{ __('forgepulse::forgepulse.workflow.description') }}</label>
                    <textarea id="step-description" wire:model="description" class="forgepulse-input" rows="3"></textarea>
                    @error('description') <span class="forgepulse-error">{{ $message }}</span> @enderror
                </div>

                {{-- Step Type (Read-only) --}}
                <div class="forgepulse-form-group">
                    <label class="forgepulse-label">{{ __('forgepulse::forgepulse.step.type') }}</label>
                    <div class="forgepulse-input-readonly">
                        {{ __('forgepulse::forgepulse.step_types.' . $type) }}
                    </div>
                </div>

            {{-- Configuration based on step type --}}
            @if($type === 'action')
                <div class="forgepulse-form-group">
                    <label class="forgepulse-label">Action Class</label>
                    <input type="text" wire:model="configuration.action_class" class="forgepulse-input" placeholder="App\Actions\MyAction">
                </div>
            @elseif($type === 'notification')
                <div class="forgepulse-form-group">
                    <label class="forgepulse-label">Notification Class</label>
                    <input type="text" wire:model="configuration.notification_class" class="forgepulse-input" placeholder="App\Notifications\MyNotification">
                </div>
            @elseif($type === 'webhook')
                <div class="forgepulse-form-group">
                    <label class="forgepulse-label">Webhook URL</label>
                    <input type="url" wire:model="configuration.url" class="forgepulse-input" placeholder="https://example.com/webhook">
                </div>
                <div class="forgepulse-form-group">
                    <label class="forgepulse-label">HTTP Method</label>
                    <select wire:model="configuration.method" class="forgepulse-select">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="PATCH">PATCH</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>
            @elseif($type === 'delay')
                <div class="forgepulse-form-group">
                    <label class="forgepulse-label">Delay (seconds)</label>
                    <input type="number" wire:model="configuration.seconds" class="forgepulse-input" min="0">
                </div>
            @endif

            {{-- Conditions --}}
            <div class="forgepulse-form-group">
                <label class="forgepulse-label">Conditions (Optional)</label>
                <div class="forgepulse-conditions">
                    @if(isset($conditions['rules']) && is_array($conditions['rules']))
                        @foreach($conditions['rules'] as $index => $rule)
                            <div class="forgepulse-condition-rule">
                                <input type="text" wire:model="conditions.rules.{{ $index }}.field" class="forgepulse-input forgepulse-input-sm" placeholder="Field">
                                <select wire:model="conditions.rules.{{ $index }}.operator" class="forgepulse-select forgepulse-select-sm">
                                    <option value="==">Equals</option>
                                    <option value="!=">Not Equals</option>
                                    <option value=">">Greater Than</option>
                                    <option value="<">Less Than</option>
                                    <option value="contains">Contains</option>
                                </select>
                                <input type="text" wire:model="conditions.rules.{{ $index }}.value" class="forgepulse-input forgepulse-input-sm" placeholder="Value">
                                <button wire:click="removeCondition({{ $index }})" class="forgepulse-btn forgepulse-btn-sm forgepulse-btn-danger">Remove</button>
                            </div>
                        @endforeach
                    @endif
                    <button wire:click="addCondition" class="forgepulse-btn forgepulse-btn-sm">Add Condition</button>
                </div>
            </div>

            <div class="forgepulse-form-group">
                <label class="forgepulse-checkbox">
                    <input type="checkbox" wire:model="isEnabled">
                    <span>Step Enabled</span>
                </label>
            </div>
        </div>

        <div class="forgepulse-modal-footer">
            <button @click="$dispatch('close-modal')" class="forgepulse-btn">Cancel</button>
            <button wire:click="save" class="forgepulse-btn forgepulse-btn-primary">Save Changes</button>
        </div>
    </div>
</div>
