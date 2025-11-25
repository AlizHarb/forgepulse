<div class="flowforge-execution-tracker" @if($this->pollingInterval) wire:poll.{{ $this->pollingInterval }}ms="refreshExecution" @endif>
    <div class="flowforge-execution-header">
        <h3 class="flowforge-execution-title">{{ __('flowforge::flowforge.execution.title') }}</h3>
        <div class="flowforge-execution-status">
            <span class="flowforge-status-badge flowforge-status-{{ $execution->status->value }}">
                {{ __('flowforge::flowforge.status.' . $execution->status->value) }}
            </span>
        </div>
    </div>

    <div class="flowforge-execution-details">
        <div class="flowforge-detail-item">
            <span class="flowforge-detail-label">{{ __('flowforge::flowforge.execution.started_at') }}:</span>
            <span class="flowforge-detail-value">{{ $execution->started_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
        </div>
        <div class="flowforge-detail-item">
            <span class="flowforge-detail-label">{{ __('flowforge::flowforge.execution.completed_at') }}:</span>
            <span class="flowforge-detail-value">{{ $execution->completed_at?->format('Y-m-d H:i:s') ?? '-' }}</span>
        </div>
        <div class="flowforge-detail-item">
            <span class="flowforge-detail-label">{{ __('flowforge::flowforge.execution.duration') }}:</span>
            <span class="flowforge-detail-value">{{ $execution->duration ? $execution->duration . 's' : '-' }}</span>
        </div>
    </div>

    @if($execution->error_message)
        <div class="flowforge-alert flowforge-alert-danger">
            <strong>Error:</strong> {{ $execution->error_message }}
        </div>
    @endif

    <div class="flowforge-execution-logs">
        <h4 class="flowforge-logs-title">Execution Steps</h4>
        <div class="flowforge-logs-list">
            @forelse($this->logs as $log)
                <div class="flowforge-log-item flowforge-log-{{ $log['status'] }}">
                    <div class="flowforge-log-header">
                        <div class="flowforge-log-step">
                            <span class="flowforge-log-icon">
                                @if($log['status'] === 'completed')
                                    <svg class="flowforge-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($log['status'] === 'failed')
                                    <svg class="flowforge-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @elseif($log['status'] === 'running')
                                    <svg class="flowforge-icon-sm flowforge-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                @else
                                    <svg class="flowforge-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </span>
                            <span class="flowforge-log-name">{{ $log['step_name'] }}</span>
                            <span class="flowforge-log-type">{{ $log['step_type'] }}</span>
                        </div>
                        <div class="flowforge-log-meta">
                            @if($log['execution_time_ms'])
                                <span class="flowforge-log-time">{{ $log['execution_time_ms'] }}ms</span>
                            @endif
                            <span class="flowforge-log-status">{{ ucfirst($log['status']) }}</span>
                        </div>
                    </div>
                    @if($log['error_message'])
                        <div class="flowforge-log-error">
                            {{ $log['error_message'] }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="flowforge-empty-state">
                    <p>No execution logs yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
