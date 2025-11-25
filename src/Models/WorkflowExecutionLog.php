<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Models;

use AlizHarb\FlowForge\Enums\LogStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * WorkflowExecutionLog Model
 *
 * Detailed log entry for a single step execution within a workflow execution.
 * Tracks input/output data, execution time, and any errors that occurred.
 * Provides granular visibility into workflow execution for debugging and monitoring.
 *
 * @author Ali Harb <harbzali@gmail.com>
 *
 * @property int $id Primary key
 * @property int $workflow_execution_id Parent execution ID
 * @property int $workflow_step_id Step that was executed
 * @property LogStatus $status Log entry status
 * @property \ArrayObject<string, mixed>|null $input Input data passed to the step
 * @property \ArrayObject<string, mixed>|null $output Output data returned from the step
 * @property string|null $error_message Error message if step failed
 * @property int|null $execution_time_ms Execution time in milliseconds
 * @property \Illuminate\Support\Carbon|null $started_at Step start time
 * @property \Illuminate\Support\Carbon|null $completed_at Step completion time
 * @property \Illuminate\Support\Carbon $created_at Creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last update timestamp
 * @property-read WorkflowExecution $execution
 * @property-read WorkflowStep $step
 *
 * @method static Builder<WorkflowExecutionLog> completed() Scope to only completed logs
 * @method static Builder<WorkflowExecutionLog> failed() Scope to only failed logs
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class WorkflowExecutionLog extends Model
{
    /** @use HasFactory<\AlizHarb\FlowForge\Database\Factories\WorkflowExecutionLogFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'workflow_execution_id',
        'workflow_step_id',
        'status',
        'input',
        'output',
        'error_message',
        'execution_time_ms',
        'started_at',
        'completed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LogStatus::class,
            'input' => AsArrayObject::class,
            'output' => AsArrayObject::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the workflow execution.
     *
     * @return BelongsTo<WorkflowExecution, $this>
     */
    public function execution(): BelongsTo
    {
        return $this->belongsTo(WorkflowExecution::class, 'workflow_execution_id');
    }

    /**
     * Get the workflow step.
     *
     * @return BelongsTo<WorkflowStep, $this>
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'workflow_step_id');
    }

    /**
     * Scope a query to only include completed logs.
     *
     * @param  Builder<WorkflowExecutionLog>  $query
     * @return Builder<WorkflowExecutionLog>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', LogStatus::COMPLETED);
    }

    /**
     * Scope a query to only include failed logs.
     *
     * @param  Builder<WorkflowExecutionLog>  $query
     * @return Builder<WorkflowExecutionLog>
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', LogStatus::FAILED);
    }

    /**
     * Mark the log as started.
     *
     * @param  array<string, mixed>  $input  Input data for the step
     */
    public function markAsStarted(array $input = []): void
    {
        $this->update([
            'status' => LogStatus::RUNNING,
            'input' => $input,
            'started_at' => now(),
        ]);
    }

    /**
     * Mark the log as completed.
     *
     * @param  array<string, mixed>  $output  Output data from the step
     */
    public function markAsCompleted(array $output = []): void
    {
        $executionTime = $this->started_at
            ? now()->diffInMilliseconds($this->started_at)
            : null;

        $this->update([
            'status' => LogStatus::COMPLETED,
            'output' => $output,
            'execution_time_ms' => $executionTime,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark the log as failed.
     *
     * @param  string  $errorMessage  Error message describing the failure
     */
    public function markAsFailed(string $errorMessage): void
    {
        $executionTime = $this->started_at
            ? now()->diffInMilliseconds($this->started_at)
            : null;

        $this->update([
            'status' => LogStatus::FAILED,
            'error_message' => $errorMessage,
            'execution_time_ms' => $executionTime,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark the log as skipped.
     */
    public function markAsSkipped(): void
    {
        $this->update([
            'status' => LogStatus::SKIPPED,
            'completed_at' => now(),
        ]);
    }
}
