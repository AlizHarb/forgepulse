<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Models;

use AlizHarb\FlowForge\Enums\ExecutionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * WorkflowExecution Model
 *
 * Tracks a single execution instance of a workflow. Each execution maintains its own
 * context, status, and timing information. Executions can be retried on failure and
 * provide detailed logging through execution logs.
 *
 * @author Ali Harb <harbzali@gmail.com>
 *
 * @property int $id Primary key
 * @property int $workflow_id Workflow being executed
 * @property int|null $user_id User who initiated the execution
 * @property ExecutionStatus $status Current execution status
 * @property \ArrayObject<string, mixed>|null $context Input context data for execution
 * @property \ArrayObject<string, mixed>|null $output Final output data from execution
 * @property string|null $error_message Error message if execution failed
 * @property int $retry_count Number of retry attempts
 * @property \Illuminate\Support\Carbon|null $started_at Execution start time
 * @property \Illuminate\Support\Carbon|null $completed_at Execution completion time
 * @property \Illuminate\Support\Carbon|null $paused_at Execution pause time
 * @property string|null $pause_reason Reason for pausing execution
 * @property \Illuminate\Support\Carbon|null $scheduled_at Scheduled execution time
 * @property \ArrayObject<string, mixed>|null $schedule_config Recurring schedule configuration
 * @property \Illuminate\Support\Carbon $created_at Creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last update timestamp
 * @property-read int|null $duration Execution duration in seconds (computed)
 * @property-read Workflow $workflow
 * @property-read \Illuminate\Database\Eloquent\Model|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WorkflowExecutionLog> $logs
 *
 * @method static Builder<WorkflowExecution> pending() Scope to only pending executions
 * @method static Builder<WorkflowExecution> running() Scope to only running executions
 * @method static Builder<WorkflowExecution> completed() Scope to only completed executions
 * @method static Builder<WorkflowExecution> failed() Scope to only failed executions
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class WorkflowExecution extends Model
{
    /** @use HasFactory<\AlizHarb\FlowForge\Database\Factories\WorkflowExecutionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'workflow_id',
        'user_id',
        'status',
        'context',
        'output',
        'error_message',
        'retry_count',
        'started_at',
        'completed_at',
        'paused_at',
        'pause_reason',
        'scheduled_at',
        'schedule_config',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ExecutionStatus::class,
            'context' => AsArrayObject::class,
            'output' => AsArrayObject::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'paused_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'schedule_config' => AsArrayObject::class,
        ];
    }

    /**
     * Get the workflow being executed.
     *
     * @return BelongsTo<Workflow, $this>
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the user who initiated the execution.
     *
     * @return BelongsTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function user(): BelongsTo
    {
        /** @var class-string<\Illuminate\Database\Eloquent\Model> $model */
        $model = config('auth.providers.users.model');

        return $this->belongsTo($model);
    }

    /**
     * Get the execution logs.
     *
     * @return HasMany<WorkflowExecutionLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WorkflowExecutionLog::class)->orderBy('created_at');
    }

    /**
     * Scope a query to only include pending executions.
     *
     * @param  Builder<WorkflowExecution>  $query
     * @return Builder<WorkflowExecution>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ExecutionStatus::PENDING);
    }

    /**
     * Scope a query to only include running executions.
     *
     * @param  Builder<WorkflowExecution>  $query
     * @return Builder<WorkflowExecution>
     */
    public function scopeRunning(Builder $query): Builder
    {
        return $query->where('status', ExecutionStatus::RUNNING);
    }

    /**
     * Scope a query to only include completed executions.
     *
     * @param  Builder<WorkflowExecution>  $query
     * @return Builder<WorkflowExecution>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', ExecutionStatus::COMPLETED);
    }

    /**
     * Scope a query to only include failed executions.
     *
     * @param  Builder<WorkflowExecution>  $query
     * @return Builder<WorkflowExecution>
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', ExecutionStatus::FAILED);
    }

    /**
     * Mark the execution as started.
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => ExecutionStatus::RUNNING,
            'started_at' => now(),
        ]);
    }

    /**
     * Mark the execution as completed.
     *
     * @param  array<string, mixed>  $output  Final output data
     */
    public function markAsCompleted(array $output = []): void
    {
        $this->update([
            'status' => ExecutionStatus::COMPLETED,
            'output' => $output,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark the execution as failed.
     *
     * @param  string  $errorMessage  Error message describing the failure
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => ExecutionStatus::FAILED,
            'error_message' => $errorMessage,
            'completed_at' => now(),
        ]);
    }

    /**
     * Increment the retry count.
     */
    public function incrementRetryCount(): void
    {
        $this->increment('retry_count');
    }

    /**
     * Check if the execution can be retried.
     *
     * @return bool True if execution can be retried
     */
    public function canRetry(): bool
    {
        $maxRetries = config('flowforge.execution.max_retries', 3);

        return $this->status === ExecutionStatus::FAILED && $this->retry_count < $maxRetries;
    }

    /**
     * Get the execution duration in seconds.
     *
     * @return Attribute<int|null, never>
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function (): ?int {
                if (! $this->started_at) {
                    return null;
                }

                $endTime = $this->completed_at ?? now();

                return (int) $this->started_at->diffInSeconds($endTime);
            }
        );
    }

    /**
     * Check if the execution is in progress.
     *
     * @return bool True if execution is pending or running
     */
    public function isInProgress(): bool
    {
        return $this->status->isInProgress();
    }

    /**
     * Check if the execution is finished.
     *
     * @return bool True if execution is completed, failed, or cancelled
     */
    public function isFinished(): bool
    {
        return $this->status->isFinished();
    }

    /**
     * Pause the execution.
     *
     * @param  string|null  $reason  Reason for pausing
     */
    public function pause(?string $reason = null): void
    {
        $this->update([
            'paused_at' => now(),
            'pause_reason' => $reason,
        ]);
    }

    /**
     * Resume the execution.
     */
    public function resume(): void
    {
        $this->update([
            'paused_at' => null,
            'pause_reason' => null,
        ]);
    }

    /**
     * Check if the execution is paused.
     *
     * @return bool True if execution is paused
     */
    public function isPaused(): bool
    {
        return $this->paused_at !== null;
    }
}
