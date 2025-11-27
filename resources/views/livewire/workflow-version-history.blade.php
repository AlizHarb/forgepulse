<div>
    <div class="forgepulse-modal" x-data="{ show: true }" x-show="show" x-cloak>
        <div class="forgepulse-modal-backdrop" @click="$dispatch('close-version-history')"></div>

        <div class="forgepulse-modal-content" @click.stop style="max-width: 900px;">
            {{-- Header --}}
            <div class="forgepulse-modal-header">
                <h3 class="forgepulse-modal-title">
                    <svg class="forgepulse-icon" style="display: inline-block; vertical-align: middle;" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('forgepulse::forgepulse.version_history.title') }} - {{ $workflow->name }}
                </h3>
                <button @click="$dispatch('close-version-history')" class="forgepulse-modal-close">&times;</button>
            </div>

            {{-- Body --}}
            <div class="forgepulse-modal-body">
                @if($versions->isEmpty())
                    <div style="text-align: center; padding: 2rem; color: var(--forgepulse-gray-500);">
                        <svg class="forgepulse-icon" style="width: 3rem; height: 3rem; margin: 0 auto 1rem;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>{{ __('forgepulse::forgepulse.version_history.no_versions') }}</p>
                    </div>
                @else
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        {{-- Version List --}}
                        <div>
                            <h4
                                style="font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; color: var(--forgepulse-gray-700);">
                                {{ __('forgepulse::forgepulse.version_history.versions') }} ({{ $versions->count() }})
                            </h4>
                            <div style="max-height: 400px; overflow-y: auto;">
                                @foreach($versions as $version)
                                    <div wire:key="version-{{ $version->id }}"
                                        class="forgepulse-version-item @if($selectedVersionId === $version->id) forgepulse-version-selected @endif"
                                        wire:click="selectVersion({{ $version->id }})" style="cursor: pointer;">
                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span class="forgepulse-badge forgepulse-badge-primary" style="font-weight: 600;">
                                                    v{{ $version->version_number }}
                                                </span>
                                                @if($version->restored_at)
                                                    <span class="forgepulse-badge forgepulse-badge-success" style="font-size: 0.75rem;">
                                                        {{ __('forgepulse::forgepulse.version_history.restored') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span style="font-size: 0.75rem; color: var(--forgepulse-gray-500);">
                                                {{ $version->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div
                                            style="font-size: 0.875rem; color: var(--forgepulse-gray-700); margin-bottom: 0.25rem;">
                                            {{ $version->description }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--forgepulse-gray-500);">
                                            {{ count($version->steps_snapshot) }}
                                            {{ __('forgepulse::forgepulse.version_history.steps') }}
                                            @if($version->creator)
                                                • {{ __('forgepulse::forgepulse.version_history.by') }}
                                                {{ $version->creator->name ?? $version->creator->email }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Version Details --}}
                        <div>
                            @if($selectedVersionId)
                                @php
                                    $selectedVersion = $versions->firstWhere('id', $selectedVersionId);
                                @endphp

                                @if($selectedVersion)
                                    <h4
                                        style="font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; color: var(--forgepulse-gray-700);">
                                        {{ __('forgepulse::forgepulse.version_history.version_details') }}
                                    </h4>

                                    <div class="forgepulse-version-details">
                                        <div class="forgepulse-detail-row">
                                            <span
                                                class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.version_history.version_number') }}:</span>
                                            <span class="forgepulse-detail-value">{{ $selectedVersion->version_number }}</span>
                                        </div>
                                        <div class="forgepulse-detail-row">
                                            <span
                                                class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.version_history.created') }}:</span>
                                            <span
                                                class="forgepulse-detail-value">{{ $selectedVersion->created_at->format('Y-m-d H:i:s') }}</span>
                                        </div>
                                        <div class="forgepulse-detail-row">
                                            <span
                                                class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.version_history.steps_count') }}:</span>
                                            <span class="forgepulse-detail-value">{{ count($selectedVersion->steps_snapshot) }}</span>
                                        </div>
                                        @if($selectedVersion->restored_at)
                                            <div class="forgepulse-detail-row">
                                                <span
                                                    class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.version_history.restored_at') }}:</span>
                                                <span
                                                    class="forgepulse-detail-value">{{ $selectedVersion->restored_at->format('Y-m-d H:i:s') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Version Diff --}}
                                    @if($versionDiff)
                                        <div
                                            style="margin-top: 1.5rem; padding: 1rem; background: var(--forgepulse-gray-50); border-radius: 0.375rem;">
                                            <h5 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem;">
                                                {{ __('forgepulse::forgepulse.version_history.comparison') }}
                                            </h5>
                                            <div style="font-size: 0.8125rem;">
                                                <div>{{ __('forgepulse::forgepulse.version_history.steps_added') }}:
                                                    <strong>{{ $versionDiff['steps_added'] }}</strong></div>
                                                <div>{{ __('forgepulse::forgepulse.version_history.steps_modified') }}:
                                                    <strong>{{ $versionDiff['steps_modified'] }}</strong></div>
                                                <div>{{ __('forgepulse::forgepulse.version_history.config_changed') }}:
                                                    <strong>{{ $versionDiff['configuration_changed'] ? __('forgepulse::forgepulse.yes') : __('forgepulse::forgepulse.no') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Actions --}}
                                    <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                                        @if($versions->count() > 1 && !$compareVersionId)
                                            <button
                                                wire:click="compareVersions({{ $selectedVersionId }}, {{ $versions->where('id', '!=', $selectedVersionId)->first()->id }})"
                                                class="forgepulse-btn forgepulse-btn-sm">
                                                <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                {{ __('forgepulse::forgepulse.version_history.compare') }}
                                            </button>
                                        @endif

                                        @if($selectedVersion->version_number !== $workflow->latestVersion()?->version_number)
                                            <button wire:click="confirmRollback({{ $selectedVersionId }})"
                                                class="forgepulse-btn forgepulse-btn-sm forgepulse-btn-primary">
                                                <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                {{ __('forgepulse::forgepulse.version_history.restore') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div style="text-align: center; padding: 3rem; color: var(--forgepulse-gray-400);">
                                    <svg class="forgepulse-icon" style="width: 2.5rem; height: 2.5rem; margin: 0 auto 1rem;"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                    </svg>
                                    <p>{{ __('forgepulse::forgepulse.version_history.select_version') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Rollback Confirmation Dialog --}}
            @if($showRollbackConfirm)
                <div class="forgepulse-modal" style="z-index: 60;">
                    <div class="forgepulse-modal-backdrop" @click="$wire.cancelRollback()"></div>
                    <div class="forgepulse-modal-content" @click.stop style="max-width: 500px;">
                        <div class="forgepulse-modal-header">
                            <h3 class="forgepulse-modal-title">
                                {{ __('forgepulse::forgepulse.version_history.confirm_rollback') }}</h3>
                        </div>
                        <div class="forgepulse-modal-body">
                            <p>{{ __('forgepulse::forgepulse.version_history.rollback_warning') }}</p>
                            <p
                                style="margin-top: 1rem; padding: 0.75rem; background: var(--forgepulse-warning); color: white; border-radius: 0.375rem; font-size: 0.875rem;">
                                <strong>{{ __('forgepulse::forgepulse.version_history.note') }}:</strong>
                                {{ __('forgepulse::forgepulse.version_history.backup_created') }}
                            </p>
                        </div>
                        <div class="forgepulse-modal-footer">
                            <button wire:click="cancelRollback" class="forgepulse-btn">
                                {{ __('forgepulse::forgepulse.cancel') }}
                            </button>
                            <button wire:click="executeRollback" class="forgepulse-btn forgepulse-btn-primary">
                                {{ __('forgepulse::forgepulse.version_history.confirm_restore') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .forgepulse-version-item {
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: white;
            border: 1px solid var(--forgepulse-gray-200);
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .forgepulse-version-item:hover {
            border-color: var(--forgepulse-primary);
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.1);
        }

        .forgepulse-version-selected {
            border-color: var(--forgepulse-primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .forgepulse-version-details {
            background: white;
            border: 1px solid var(--forgepulse-gray-200);
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .forgepulse-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--forgepulse-gray-100);
            font-size: 0.875rem;
        }

        .forgepulse-detail-row:last-child {
            border-bottom: none;
        }

        .forgepulse-detail-label {
            color: var(--forgepulse-gray-600);
            font-weight: 500;
        }

        .forgepulse-detail-value {
            color: var(--forgepulse-gray-900);
        }

        [data-theme="dark"] .forgepulse-version-item {
            background: var(--forgepulse-gray-100);
            border-color: var(--forgepulse-gray-200);
        }

        [data-theme="dark"] .forgepulse-version-details {
            background: var(--forgepulse-gray-100);
            border-color: var(--forgepulse-gray-200);
        }
    </style>
</div>
