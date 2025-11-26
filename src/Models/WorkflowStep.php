<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Models;

use AlizHarb\FlowForge\Enums\StepType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * WorkflowStep Model
 *
 * Represents a single step in a workflow with conditional logic and configuration.
 * Steps can be chained together using parent-child relationships to create complex
 * workflow structures. Each step has a specific type (action, condition, delay, etc.)
 * and can have conditional logic to determine if it should execute.
 *
 * @author Ali Harb <harbzali@gmail.com>
 *
 * @property int $id Primary key
 * @property int $workflow_id Parent workflow ID
 * @property int|null $parent_step_id Parent step ID for chaining
 * @property string $name Step name
 * @property string|null $description Step description
 * @property StepType $type Step type (action, condition, delay, notification, webhook, event, job)
 * @property \ArrayObject<string, mixed> $configuration JSON configuration data for the step
 * @property \ArrayObject<string, mixed>|null $conditions Conditional logic rules
 * @property int $position Step position in workflow (for ordering)
 * @property int|null $x_position X coordinate on canvas
 * @property int|null $y_position Y coordinate on canvas
 * @property bool $is_enabled Whether the step is enabled
 * @property int|null $timeout Timeout in seconds
 * @property string $execution_mode Execution mode (sequential or parallel)
 * @property string|null $parallel_group Parallel group identifier
 * @property \Illuminate\Support\Carbon $created_at Creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last update timestamp
 * @property-read Workflow $workflow
 * @property-read WorkflowStep|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WorkflowStep> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WorkflowExecutionLog> $executionLogs
 *
 * @method static Builder<WorkflowStep> enabled() Scope to only enabled steps
 * @method static Builder<WorkflowStep> roots() Scope to only root steps (no parent)
 * @method static Builder<WorkflowStep> ofType(StepType $type) Scope to filter by step type
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class WorkflowStep extends Model
{
    /** @use HasFactory<\AlizHarb\FlowForge\Database\Factories\WorkflowStepFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'workflow_id',
        'parent_step_id',
        'name',
        'description',
        'type',
        'configuration',
        'conditions',
        'position',
        'x_position',
        'y_position',
        'is_enabled',
        'timeout',
        'execution_mode',
        'parallel_group',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => StepType::class,
            'configuration' => AsArrayObject::class,
            'conditions' => AsArrayObject::class,
            'is_enabled' => 'boolean',
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        /** @var \Illuminate\Database\Eloquent\Factories\Factory<static> */
        return \AlizHarb\FlowForge\Database\Factories\WorkflowStepFactory::new();
    }

    /**
     * Get the workflow that owns the step.
     *
     * @return BelongsTo<Workflow, $this>
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the parent step.
     *
     * @return BelongsTo<WorkflowStep, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_step_id');
    }

    /**
     * Get the child steps.
     *
     * @return HasMany<WorkflowStep, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_step_id');
    }

    /**
     * Get the execution logs for this step.
     *
     * @return HasMany<WorkflowExecutionLog, $this>
     */
    public function executionLogs(): HasMany
    {
        return $this->hasMany(WorkflowExecutionLog::class);
    }

    /**
     * Scope a query to only include enabled steps.
     *
     * @param  Builder<WorkflowStep>  $query
     * @return Builder<WorkflowStep>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope a query to only include root steps (no parent).
     *
     * @param  Builder<WorkflowStep>  $query
     * @return Builder<WorkflowStep>
     */
    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_step_id');
    }

    /**
     * Scope a query to filter by step type.
     *
     * @param  Builder<WorkflowStep>  $query
     * @param  StepType  $type  Step type to filter by
     * @return Builder<WorkflowStep>
     */
    public function scopeOfType(Builder $query, StepType $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Check if this step has conditions.
     *
     * @return bool True if step has conditional logic
     */
    public function hasConditions(): bool
    {
        return ! empty($this->conditions);
    }

    /**
     * Evaluate the step's conditions against the given context.
     *
     * @param  array<string, mixed>  $context  Execution context data
     * @return bool True if conditions pass (or no conditions exist)
     */
    public function evaluateConditions(array $context): bool
    {
        if (! $this->hasConditions() || $this->conditions === null) {
            return true;
        }

        return app(\AlizHarb\FlowForge\Services\ConditionalEvaluator::class)
            ->evaluate($this->conditions->getArrayCopy(), $context);
    }

    /**
     * Get the handler class for this step type.
     *
     * @return class-string Handler class name
     */
    public function getHandlerClass(): string
    {
        /** @var class-string */
        return $this->type->handlerClass();
    }

    /**
     * Execute this step with the given context.
     *
     * @param  array<string, mixed>  $context  Execution context data
     * @return array<string, mixed> Output data from step execution
     *
     * @throws \RuntimeException If handler class not found or invalid
     */
    public function execute(array $context): array
    {
        $handler = app($this->getHandlerClass());

        /** @var mixed $handler */
        return $handler->handle($this, $context);
    }

    /**
     * Get the default configuration for this step's type.
     *
     * @return array<string, mixed> Default configuration array
     */
    public function getDefaultConfiguration(): array
    {
        return $this->type->defaultConfiguration();
    }

    /**
     * Get the timeout for this step in seconds.
     *
     * @return int|null Timeout in seconds or null if no timeout
     */
    public function getTimeout(): ?int
    {
        return $this->timeout;
    }
}
