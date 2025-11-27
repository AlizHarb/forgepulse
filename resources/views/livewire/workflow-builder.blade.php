<div class="forgepulse-workflow-builder"
<div class="forgepulse-workflow-builder"
    x-data="workflowBuilder(@entangle('steps'), @entangle('selectedStepId'), {{ $canvasZoom }}, @entangle('gridSnap'))">
    {{-- Toolbar --}}
    {{-- Toolbar --}}
    <div class="forgepulse-toolbar">
        {{-- Left Section: History & Undo/Redo --}}
        <div class="forgepulse-toolbar-section">
            <button @click="showVersionHistory = true" class="forgepulse-btn forgepulse-btn-icon" data-tooltip="Version History">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <div class="w-px h-6 bg-gray-300 dark:bg-gray-700 mx-2"></div>
            <button @click="undo()" class="forgepulse-btn forgepulse-btn-icon" :disabled="historyIndex <= 0" data-tooltip="Undo (⌘Z)">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
            </button>
            <button @click="redo()" class="forgepulse-btn forgepulse-btn-icon" :disabled="historyIndex >= history.length - 1" data-tooltip="Redo (⌘⇧Z)">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6" />
                </svg>
            </button>
        </div>

        {{-- Center Section: Step Types --}}
        <div class="forgepulse-toolbar-section">
            <button @click="addStep('action')" class="forgepulse-btn" data-tooltip="Add Action">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="hidden sm:inline">Action</span>
            </button>
            <button @click="addStep('condition')" class="forgepulse-btn" data-tooltip="Add Condition">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                </svg>
                <span class="hidden sm:inline">Condition</span>
            </button>
            <button @click="addStep('notification')" class="forgepulse-btn" data-tooltip="Add Notification">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <button @click="addStep('webhook')" class="forgepulse-btn" data-tooltip="Add Webhook">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </button>
        </div>

        {{-- Right Section: Zoom & Save --}}
        <div class="forgepulse-toolbar-section">
            <button wire:click="zoomOut" class="forgepulse-btn forgepulse-btn-icon" data-tooltip="Zoom Out">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 w-8 text-center">{{ $canvasZoom }}%</span>
            <button wire:click="zoomIn" class="forgepulse-btn forgepulse-btn-icon" data-tooltip="Zoom In">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            
            <div class="w-px h-6 bg-gray-300 dark:bg-gray-700 mx-2"></div>
            
            <button @click="gridSnap = !gridSnap" class="forgepulse-btn forgepulse-btn-icon" 
                    :class="{ 'bg-indigo-100 dark:bg-indigo-900': gridSnap }" 
                    data-tooltip="Grid Snap">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                </svg>
            </button>
            
            <div class="w-px h-6 bg-gray-300 dark:bg-gray-700 mx-2"></div>
            
            <button wire:click="save" class="forgepulse-btn forgepulse-btn-primary" data-tooltip="Save Workflow (⌘S)">
                <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span class="hidden sm:inline">Save</span>
            </button>
        </div>
    </div>

    {{-- Canvas --}}
    <div class="forgepulse-canvas-container">
        <div class="forgepulse-canvas" :style="`transform: scale(${zoom / 100})`" x-ref="canvas"
            @mousedown="startPan($event)" @mousemove="pan($event)" @mouseup="endPan">

            {{-- SVG for connections --}}
            <svg style="position: absolute; width: 0; height: 0; overflow: hidden;" aria-hidden="true">
                <defs>
                    <marker id="arrowhead" markerWidth="10" markerHeight="10" refX="9" refY="3" orient="auto">
                        <polygon points="0 0, 10 3, 0 6" fill="#6366f1" />
                    </marker>
                </defs>
            </svg>
            <div class="forgepulse-connections" x-ref="connections">
                <template x-for="connection in connections" :key="connection.id">
                    <svg class="absolute inset-0 w-full h-full pointer-events-none" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: visible;">
                        <line :x1="connection.x1" :y1="connection.y1" :x2="connection.x2" :y2="connection.y2"
                            class="forgepulse-connection-line" stroke="#6366f1" stroke-width="2"
                            marker-end="url(#arrowhead)" />
                    </svg>
                </template>
            </div>

            {{-- Workflow Steps --}}
            <template x-for="step in steps" :key="step.id">
                <div class="forgepulse-step" :class="{
                         'forgepulse-step-selected': selectedStepId === step.id,
                         'forgepulse-step-disabled': !step.is_enabled,
                         [`forgepulse-step-${step.type}`]: true
                     }" :style="`left: ${step.x_position}px; top: ${step.y_position}px;`"
                    @mousedown.stop="startDrag($event, step)" 
                    @click="handleStepClick($event, step.id)"
                    @dblclick="$wire.selectStep(step.id)">

                    <div class="forgepulse-step-header">
                        <div class="forgepulse-step-icon" :class="`forgepulse-step-icon-${step.type}`">
                            <svg class="forgepulse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="getStepIcon(step.type)" />
                            </svg>
                        </div>
                        <span class="forgepulse-step-name" x-text="step.name"></span>
                    </div>

                    <div class="forgepulse-step-actions">
                        <button @click.stop="$wire.toggleStepEnabled(step.id)" class="forgepulse-step-action">
                            <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="step.is_enabled ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' : 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21'" />
                            </svg>
                        </button>
                        <button @click.stop="$wire.deleteStep(step.id)"
                            class="forgepulse-step-action forgepulse-step-action-danger">
                            <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="step.has_conditions" class="forgepulse-step-badge">
                        <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                        </svg>
                    </div>
                    
                    {{-- Link indicator --}}
                    <div x-show="step.parent_step_id" class="forgepulse-step-link-indicator" 
                         data-tooltip="Linked to parent step">
                        <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Step Editor Modal --}}
    @if($showStepEditor && $selectedStepId)
        <livewire:forgepulse.workflow-step-editor :stepId="$selectedStepId" :key="'step-editor-' . $selectedStepId" />
    @endif

    {{-- Version History Modal --}}
    <div x-show="showVersionHistory" 
         x-cloak
         @close-version-history.window="showVersionHistory = false">
        <livewire:forgepulse.workflow-version-history :workflow="$workflow" :key="'version-history-' . $workflow->id" />
    </div>

    {{-- Minimap --}}
    <div class="forgepulse-minimap forgepulse-draggable" 
         x-show="steps.length > 0"
         @mousedown="startDragElement($event, $el)"
         @click="handleMinimapClick($event)">
        <div class="forgepulse-minimap-content">
            <template x-for="step in steps" :key="step.id">
                <div class="forgepulse-minimap-node"
                    :style="`left: ${step.x_position * 0.1}px; top: ${step.y_position * 0.1}px;`"
                    :class="{'forgepulse-minimap-node-selected': selectedStepId === step.id}"
                    @click.stop="selectStep(step.id)">
                </div>
            </template>
            <div class="forgepulse-minimap-viewport"></div>
        </div>
    </div>

    {{-- Keyboard Shortcuts Hint --}}
    <div class="forgepulse-shortcuts-hint forgepulse-draggable"
         @mousedown="startDragElement($event, $el)">
        <div class="forgepulse-shortcut-item">
            <span class="forgepulse-kbd">⌘S</span> Save
        </div>
        <div class="forgepulse-shortcut-item">
            <span class="forgepulse-kbd">⌘Z</span> Undo
        </div>
        <div class="forgepulse-shortcut-item">
            <span class="forgepulse-kbd">⌘⇧Z</span> Redo
        </div>
        <div class="forgepulse-shortcut-item">
            <span class="forgepulse-kbd">⌫</span> Delete
        </div>
        <div class="forgepulse-shortcut-item">
            <span class="forgepulse-kbd">Esc</span> Deselect
        </div>
    </div>
    <style>
        .forgepulse-minimap {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 200px;
            height: 150px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--forgepulse-gray-200);
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 40;
            backdrop-filter: blur(4px);
        }

        .forgepulse-minimap-content {
            position: relative;
            width: 100%;
            height: 100%;
            transform-origin: 0 0;
        }

        .forgepulse-minimap-node {
            position: absolute;
            width: 6px;
            height: 4px;
            background: var(--forgepulse-gray-400);
            border-radius: 2px;
        }

        .forgepulse-minimap-node-selected {
            background: var(--forgepulse-primary);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        .forgepulse-shortcuts-hint {
            position: absolute;
            bottom: 20px;
            left: 20px;
            display: flex;
            gap: 12px;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--forgepulse-gray-200);
            border-radius: 8px;
            font-size: 12px;
            color: var(--forgepulse-gray-600);
            backdrop-filter: blur(4px);
            z-index: 40;
        }

        .forgepulse-shortcut-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .forgepulse-kbd {
            padding: 2px 6px;
            background: var(--forgepulse-gray-100);
            border: 1px solid var(--forgepulse-gray-300);
            border-radius: 4px;
            font-family: monospace;
            font-weight: 600;
            font-size: 11px;
        }

        [data-theme="dark"] .forgepulse-minimap,
        [data-theme="dark"] .forgepulse-shortcuts-hint {
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--forgepulse-gray-200);
            color: var(--forgepulse-gray-400);
        }

        [data-theme="dark"] .forgepulse-kbd {
            background: var(--forgepulse-gray-200);
            border-color: var(--forgepulse-gray-300);
            color: var(--forgepulse-gray-700);
        }
    </style>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('workflowBuilder', (stepsWire, selectedStepIdWire, initialZoom, gridSnapWire) => ({
                steps: stepsWire,
                selectedStepId: selectedStepIdWire,
                zoom: initialZoom,
                gridSnap: gridSnapWire,
                showVersionHistory: false,
                dragging: null,
                panning: false,
                panStart: { x: 0, y: 0 },

                history: [],
                historyIndex: -1,
                draggingElement: null,
                clickStartPos: null,
                clickStartTime: null,

                get connections() {
                    if (!this.steps) return [];
                    return this.steps
                        .filter(step => step.parent_step_id)
                        .map(step => {
                            const parent = this.getStepById(step.parent_step_id);
                            if (!parent) return null;
                            const start = this.getStepCenter(parent);
                            const end = this.getStepCenter(step);
                            return {
                                id: `conn-${parent.id}-${step.id}`,
                                x1: start.x,
                                y1: start.y,
                                x2: end.x,
                                y2: end.y
                            };
                        })
                        .filter(conn => conn !== null);
                },

                init() {
                    this.$watch('steps', () => {
                        this.drawConnections();
                        this.recordHistory();
                    });
                    this.$nextTick(() => {
                        this.drawConnections();
                        this.recordHistory(); // Initial state
                    });
                    
                    // Keyboard Shortcuts
                    window.addEventListener('keydown', (e) => {
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                        if ((e.metaKey || e.ctrlKey) && e.key === 's') {
                            e.preventDefault();
                            this.$wire.save();
                        }
                        if ((e.metaKey || e.ctrlKey) && e.key === 'z') {
                            e.preventDefault();
                            if (e.shiftKey) {
                                this.redo();
                            } else {
                                this.undo();
                            }
                        }
                        if (e.key === 'Delete' || e.key === 'Backspace') {
                            if (this.selectedStepId) {
                                this.$wire.deleteStep(this.selectedStepId);
                            }
                        }
                        if (e.key === 'Escape') {
                            this.selectedStepId = null;
                        }
                        if (e.key === '=' || e.key === '+') {
                            this.$wire.zoomIn();
                        }
                        if (e.key === '-') {
                            this.$wire.zoomOut();
                        }
                    });
                },

                recordHistory() {
                    // Debounce history recording
                    if (this._historyTimeout) clearTimeout(this._historyTimeout);
                    this._historyTimeout = setTimeout(() => {
                        const currentState = JSON.stringify(this.steps);
                        if (this.historyIndex === -1 || this.history[this.historyIndex] !== currentState) {
                            // Remove future history if we're in the middle of the stack
                            if (this.historyIndex < this.history.length - 1) {
                                this.history = this.history.slice(0, this.historyIndex + 1);
                            }
                            this.history.push(currentState);
                            this.historyIndex++;
                            // Limit history size
                            if (this.history.length > 50) {
                                this.history.shift();
                                this.historyIndex--;
                            }
                        }
                    }, 500);
                },

                undo() {
                    if (this.historyIndex > 0) {
                        this.historyIndex--;
                        const previousState = JSON.parse(this.history[this.historyIndex]);
                        this.steps = previousState;
                        // Sync with Livewire
                        this.$wire.set('steps', previousState);
                    }
                },

                redo() {
                    if (this.historyIndex < this.history.length - 1) {
                        this.historyIndex++;
                        const nextState = JSON.parse(this.history[this.historyIndex]);
                        this.steps = nextState;
                        // Sync with Livewire
                        this.$wire.set('steps', nextState);
                    }
                },

                startDragElement(event, element) {
                    if (event.target.closest('button') || event.target.closest('input')) return;
                    
                    this.draggingElement = {
                        el: element,
                        startX: event.clientX,
                        startY: event.clientY,
                        initialLeft: element.offsetLeft,
                        initialTop: element.offsetTop
                    };

                    const mousemove = (e) => {
                        if (!this.draggingElement) return;
                        const dx = e.clientX - this.draggingElement.startX;
                        const dy = e.clientY - this.draggingElement.startY;
                        
                        this.draggingElement.el.style.transform = `translate(${dx}px, ${dy}px)`;
                    };

                    const mouseup = () => {
                        this.draggingElement = null;
                        document.removeEventListener('mousemove', mousemove);
                        document.removeEventListener('mouseup', mouseup);
                    };

                    document.addEventListener('mousemove', mousemove);
                    document.addEventListener('mouseup', mouseup);
                },

                handleMinimapClick(event) {
                    // Get click position relative to minimap
                    const rect = event.currentTarget.getBoundingClientRect();
                    const x = event.clientX - rect.left;
                    const y = event.clientY - rect.top;
                    
                    // Convert minimap coordinates to canvas coordinates (scale by 10)
                    const canvasX = x * 10;
                    const canvasY = y * 10;
                    
                    // Find the closest step to this position
                    let closestStep = null;
                    let minDistance = Infinity;
                    
                    this.steps.forEach(step => {
                        const distance = Math.sqrt(
                            Math.pow(step.x_position - canvasX, 2) + 
                            Math.pow(step.y_position - canvasY, 2)
                        );
                        if (distance < minDistance) {
                            minDistance = distance;
                            closestStep = step;
                        }
                    });
                    
                    // Select the closest step if within reasonable distance
                    if (closestStep && minDistance < 200) {
                        this.selectStep(closestStep.id);
                    }
                },

                handleStepClick(event, stepId) {
                    // Check if this was a drag or a click
                    const now = Date.now();
                    const timeDiff = now - (this.clickStartTime || 0);
                    
                    if (this.clickStartPos) {
                        const dx = Math.abs(event.clientX - this.clickStartPos.x);
                        const dy = Math.abs(event.clientY - this.clickStartPos.y);
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        // If moved less than 5px and less than 300ms, treat as click
                        if (distance < 5 && timeDiff < 300) {
                            this.selectedStepId = stepId;
                        }
                    }
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
                    // Record click start position and time
                    this.clickStartPos = { x: event.clientX, y: event.clientY };
                    this.clickStartTime = Date.now();
                    
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
                            const gridSize = {{ config('forgepulse.ui.grid_size', 20) }};
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
