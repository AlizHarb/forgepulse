<div class="forgepulse-execution-tracker" @if($this->pollingInterval) wire:poll.{{ $this->pollingInterval }}ms="refreshExecution" @endif>
    <div class="forgepulse-execution-header">
        <h3 class="forgepulse-execution-title">{{ __('forgepulse::forgepulse.execution.title') }}</h3>
        <div class="forgepulse-execution-status">
            <span class="forgepulse-status-badge forgepulse-status-{{ $execution->status->value }}">
                {{ __('forgepulse::forgepulse.status.' . $execution->status->value) }}
            </span>
        </div>
    </div>

    <div class="forgepulse-execution-details">
        <div class="forgepulse-detail-item">
            <span class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.execution.started_at') }}:</span>
            <span class="forgepulse-detail-value">{{ $execution->started_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
        </div>
        <div class="forgepulse-detail-item">
            <span class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.execution.completed_at') }}:</span>
            <span class="forgepulse-detail-value">{{ $execution->completed_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
        </div>
        <div class="forgepulse-detail-item">
            <span class="forgepulse-detail-label">{{ __('forgepulse::forgepulse.execution.duration') }}:</span>
            <span class="forgepulse-detail-value">{{ $execution->duration ? $execution->duration . 's' : '-' }}</span>
        </div>
    </div>

    @if($execution->error_message)
        <div class="forgepulse-alert forgepulse-alert-danger">
            <strong>Error:</strong> {{ $execution->error_message }}
        </div>
    @endif

    <div class="forgepulse-execution-logs">
        <h4 class="forgepulse-logs-title">Execution Steps</h4>
        <div class="forgepulse-logs-list">
            @forelse($this->logs as $log)
                <div class="forgepulse-log-item forgepulse-log-{{ $log['status'] }}">
                    <div class="forgepulse-log-header">
                        <div class="forgepulse-log-step">
                            <span class="forgepulse-log-icon">
                                @if($log['status'] === 'completed')
                                    <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($log['status'] === 'failed')
                                    <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @elseif($log['status'] === 'running')
                                    <svg class="forgepulse-icon-sm forgepulse-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                @else
                                    <svg class="forgepulse-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </span>
                            <span class="forgepulse-log-name">{{ $log['step_name'] }}</span>
                            <span class="forgepulse-log-type">{{ $log['step_type'] }}</span>
                        </div>
                        <div class="forgepulse-log-meta">
                            @if($log['execution_time_ms'])
                                <span class="forgepulse-log-time">{{ $log['execution_time_ms'] }}ms</span>
                            @endif
                            <span class="forgepulse-log-status">{{ ucfirst($log['status']) }}</span>
                        </div>
                    </div>
                    @if($log['error_message'])
                        <div class="forgepulse-log-error">
                            {{ $log['error_message'] }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="forgepulse-empty-state">
                    <p>No execution logs yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
