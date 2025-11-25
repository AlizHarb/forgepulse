<div class="flowforge-workflow-builder"
    x-data="workflowBuilder(@entangle('steps'), @entangle('selectedStepId'), {{ $canvasZoom }}, {{ $gridSnap ? 'true' : 'false' }})">
    {{-- Toolbar --}}
    <div class="flowforge-toolbar">
        <div class="flowforge-toolbar-section">
            <h2 class="flowforge-workflow-title">{{ $workflow->name }}</h2>
        </div>

        <div class="flowforge-toolbar-section">
            {{-- Step Type Buttons --}}
            <button @click="addStep('action')" class="flowforge-btn flowforge-btn-sm">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                {{ __('flowforge::flowforge.step_types.action') }}
            </button>
            <button @click="addStep('condition')" class="flowforge-btn flowforge-btn-sm">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                </svg>
                {{ __('flowforge::flowforge.step_types.condition') }}
            </button>
            <button @click="addStep('notification')" class="flowforge-btn flowforge-btn-sm">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{ __('flowforge::flowforge.step_types.notification') }}
            </button>
            <button @click="addStep('webhook')" class="flowforge-btn flowforge-btn-sm">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                {{ __('flowforge::flowforge.step_types.webhook') }}
            </button>
        </div>

        <div class="flowforge-toolbar-section">
            {{-- Zoom Controls --}}
            <button wire:click="zoomOut" class="flowforge-btn flowforge-btn-icon"
                title="{{ __('flowforge::flowforge.builder.zoom_out') }}">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
            <span class="flowforge-zoom-level">{{ $canvasZoom }}%</span>
            <button wire:click="zoomIn" class="flowforge-btn flowforge-btn-icon"
                title="{{ __('flowforge::flowforge.builder.zoom_in') }}">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            <button wire:click="resetZoom" class="flowforge-btn flowforge-btn-icon"
                title="{{ __('flowforge::flowforge.builder.reset_zoom') }}">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>

            {{-- Grid Snap Toggle --}}
            <button wire:click="toggleGridSnap" class="flowforge-btn flowforge-btn-icon"
                :class="{ 'flowforge-btn-active': gridSnap }"
                title="{{ __('flowforge::flowforge.builder.grid_snap') }}">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                </svg>
            </button>

            {{-- Save Button --}}
            <button wire:click="save" class="flowforge-btn flowforge-btn-primary">
                <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                {{ __('flowforge::flowforge.builder.save') }}
            </button>
        </div>
    </div>

    {{-- Canvas --}}
    <div class="flowforge-canvas-container">
        <div class="flowforge-canvas" :style="`transform: scale(${zoom / 100})`" x-ref="canvas"
            @mousedown="startPan($event)" @mousemove="pan($event)" @mouseup="endPan">

            {{-- Grid Background --}}
            <div class="flowforge-grid" x-show="gridSnap"></div>

            {{-- Connection Lines --}}
            <svg class="flowforge-connections" x-ref="connections">
                <template x-for="step in steps" :key="step.id">
                    <template x-if="step.parent_step_id">
                        <line :x1="getStepCenter(getStepById(step.parent_step_id)).x"
                            :y1="getStepCenter(getStepById(step.parent_step_id)).y" :x2="getStepCenter(step).x"
                            :y2="getStepCenter(step).y" class="flowforge-connection-line" stroke="#6366f1"
                            stroke-width="2" marker-end="url(#arrowhead)" />
                    </template>
                </template>
                <defs>
                    <marker id="arrowhead" markerWidth="10" markerHeight="10" refX="9" refY="3" orient="auto">
                        <polygon points="0 0, 10 3, 0 6" fill="#6366f1" />
                    </marker>
                </defs>
            </svg>

            {{-- Workflow Steps --}}
            <template x-for="step in steps" :key="step.id">
                <div class="flowforge-step" :class="{
                         'flowforge-step-selected': selectedStepId === step.id,
                         'flowforge-step-disabled': !step.is_enabled,
                         [`flowforge-step-${step.type}`]: true
                     }" :style="`left: ${step.x_position}px; top: ${step.y_position}px;`"
                    @mousedown.stop="startDrag($event, step)" @click="selectStep(step.id)">

                    <div class="flowforge-step-header">
                        <div class="flowforge-step-icon" :class="`flowforge-step-icon-${step.type}`">
                            <svg class="flowforge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="getStepIcon(step.type)" />
                            </svg>
                        </div>
                        <span class="flowforge-step-name" x-text="step.name"></span>
                    </div>

                    <div class="flowforge-step-actions">
                        <button @click.stop="$wire.toggleStepEnabled(step.id)" class="flowforge-step-action">
                            <svg class="flowforge-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="step.is_enabled ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' : 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21'" />
                            </svg>
                        </button>
                        <button @click.stop="$wire.deleteStep(step.id)"
                            class="flowforge-step-action flowforge-step-action-danger">
                            <svg class="flowforge-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="step.has_conditions" class="flowforge-step-badge">
                        <svg class="flowforge-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                        </svg>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Step Editor Modal --}}
    @if($showStepEditor && $selectedStepId)
        <livewire:flowforge::workflow-step-editor :stepId="$selectedStepId" :key="'step-editor-' . $selectedStepId" />
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('workflowBuilder', (stepsWire, selectedStepIdWire, initialZoom, initialGridSnap) => ({
                steps: stepsWire,
                selectedStepId: selectedStepIdWire,
                zoom: initialZoom,
                gridSnap: initialGridSnap,
                dragging: null,
                panning: false,
                panStart: { x: 0, y: 0 },

                init() {
                    this.$watch('steps', () => this.drawConnections());
                    this.$nextTick(() => this.drawConnections());
                },

                addStep(type) {
                    const x = 200 + (this.steps.length * 50);
                    const y = 200 + (this.steps.length * 50);
                    this.$wire.addStep(type, x, y);
                },

                selectStep(stepId) {
                    this.$wire.selectStep(stepId);
                },

                startDrag(event, step) {
                    this.dragging = {
                        step: step,
                        offsetX: event.clientX - step.x_position,
                        offsetY: event.clientY - step.y_position
                    };

                    const mousemove = (e) => {
                        if (!this.dragging) return;

                        let x = e.clientX - this.dragging.offsetX;
                        let y = e.clientY - this.dragging.offsetY;

                        if (this.gridSnap) {
                            const gridSize = {{ config('flowforge.ui.grid_size', 20) }};
                            x = Math.round(x / gridSize) * gridSize;
                            y = Math.round(y / gridSize) * gridSize;
                        }

                        this.dragging.step.x_position = Math.max(0, x);
                        this.dragging.step.y_position = Math.max(0, y);
                        this.drawConnections();
                    };

                    const mouseup = () => {
                        if (this.dragging) {
                            this.$wire.updateStepPosition(
                                this.dragging.step.id,
                                this.dragging.step.x_position,
                                this.dragging.step.y_position
                            );
                            this.dragging = null;
                        }
                        document.removeEventListener('mousemove', mousemove);
                        document.removeEventListener('mouseup', mouseup);
                    };

                    document.addEventListener('mousemove', mousemove);
                    document.addEventListener('mouseup', mouseup);
                },

                getStepById(id) {
                    return this.steps.find(s => s.id === id);
                },

                getStepCenter(step) {
                    if (!step) return { x: 0, y: 0 };
                    return {
                        x: step.x_position + 100,
                        y: step.y_position + 40
                    };
                },

                getStepIcon(type) {
                    const icons = {
                        action: 'M13 10V3L4 14h7v7l9-11h-7z',
                        condition: 'M8 16l-4-4m0 0l4-4m-4 4h16',
                        delay: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        notification: 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                        webhook: 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
                        event: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        job: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
                    };
                    return icons[type] || icons.action;
                },

                drawConnections() {
                    // Connections are drawn via SVG in template
                },

                startPan(event) {
                    if (event.target === this.$refs.canvas) {
                        this.panning = true;
                        this.panStart = { x: event.clientX, y: event.clientY };
                    }
                },

                pan(event) {
                    if (!this.panning) return;
                    // Pan implementation would go here
                },

                endPan() {
                    this.panning = false;
                }
            }));
        });
    </script>
@endpush