<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Models;

use AlizHarb\FlowForge\Enums\WorkflowStatus;
use AlizHarb\FlowForge\Events\WorkflowStarted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Workflow Model
 *
 * Represents a workflow definition with steps, executions, and configuration.
 * Workflows can be active, draft, inactive, or archived. They support templating
 * for reusability and can be owned by users or teams.
 *
 * @author Ali Harb <harbzali@gmail.com>
 *
 * @property int $id Primary key
 * @property string $name Workflow name
 * @property string|null $description Workflow description
 * @property WorkflowStatus $status Current workflow status (draft, active, inactive, archived)
 * @property \ArrayObject<string, mixed>|null $configuration JSON configuration data
 * @property bool $is_template Whether this workflow is a template
 * @property int|string|null $user_id Owner user ID
 * @property int|null $team_id Owner team ID
 * @property string $version Workflow version (semantic versioning)
 * @property \Illuminate\Support\Carbon $created_at Creation timestamp
 * @property \Illuminate\Support\Carbon $updated_at Last update timestamp
 * @property \Illuminate\Support\Carbon|null $deleted_at Soft delete timestamp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WorkflowStep> $steps
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WorkflowExecution> $executions
 * @property-read \Illuminate\Database\Eloquent\Model $user
 * @property-read \Illuminate\Database\Eloquent\Model $team
 *
 * @method static Builder<Workflow> active() Scope to only active workflows
 * @method static Builder<Workflow> templates() Scope to only template workflows
 * @method static Builder<Workflow> forUser(int $userId) Scope to workflows for specific user
 * @method static Builder<Workflow> forTeam(int $teamId) Scope to workflows for specific team
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Workflow extends Model
{
    /** @use HasFactory<\AlizHarb\FlowForge\Database\Factories\WorkflowFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'configuration',
        'is_template',
        'user_id',
        'team_id',
        'version',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => WorkflowStatus::class,
            'configuration' => AsArrayObject::class,
            'is_template' => 'boolean',
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        /** @var \Illuminate\Database\Eloquent\Factories\Factory<static> */
        return \AlizHarb\FlowForge\Database\Factories\WorkflowFactory::new();
    }

    /**
     * Get the user that owns the workflow.
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
     * Get the team that owns the workflow.
     *
     * @return BelongsTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function team(): BelongsTo
    {
        /** @var class-string<\Illuminate\Database\Eloquent\Model> $model */
        $model = config('flowforge.teams.model', 'App\\Models\\Team');

        return $this->belongsTo($model);
    }

    /**
     * Get the steps for the workflow.
     *
     * @return HasMany<WorkflowStep, $this>
     */
    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class)->orderBy('position');
    }

    /**
     * Get the executions for the workflow.
     *
     * @return HasMany<WorkflowExecution, $this>
     */
    public function executions(): HasMany
    {
        return $this->hasMany(WorkflowExecution::class);
    }

    /**
     * Scope a query to only include active workflows.
     *
     * @param  Builder<Workflow>  $query
     * @return Builder<Workflow>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', WorkflowStatus::ACTIVE);
    }

    /**
     * Scope a query to only include templates.
     *
     * @param  Builder<Workflow>  $query
     * @return Builder<Workflow>
     */
    public function scopeTemplates(Builder $query): Builder
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope a query to only include workflows for a specific user.
     *
     * @param  Builder<Workflow>  $query
     * @param  int  $userId  User ID to filter by
     * @return Builder<Workflow>
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include workflows for a specific team.
     *
     * @param  Builder<Workflow>  $query
     * @param  int  $teamId  Team ID to filter by
     * @return Builder<Workflow>
     */
    public function scopeForTeam(Builder $query, int $teamId): Builder
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Execute the workflow with the given context.
     *
     * @param  array<string, mixed>  $context  Input data for the workflow
     * @param  bool|null  $async  Whether to execute asynchronously (null = use config default)
     * @return WorkflowExecution The created execution instance
     *
     * @throws \RuntimeException If workflow cannot be executed
     */
    public function execute(array $context = [], ?bool $async = null): WorkflowExecution
    {
        $async ??= config('flowforge.execution.async_by_default', true);

        $execution = $this->executions()->create([
            'user_id' => auth()->id(),
            'status' => \AlizHarb\FlowForge\Enums\ExecutionStatus::PENDING,
            'context' => $context,
        ]);

        event(new WorkflowStarted($execution));

        if ($async) {
            \AlizHarb\FlowForge\Jobs\ExecuteWorkflowJob::dispatch($execution);
        } else {
            app(\AlizHarb\FlowForge\Services\WorkflowEngine::class)->execute($execution);
        }

        return $execution;
    }

    /**
     * Validate the workflow structure.
     *
     * @return bool True if workflow is valid
     *
     * @throws \RuntimeException If workflow structure is invalid
     */
    public function validate(): bool
    {
        return app(\AlizHarb\FlowForge\Services\WorkflowValidator::class)->validate($this);
    }

    /**
     * Clone this workflow as a template.
     *
     * @param  string|null  $name  Template name (defaults to "{workflow name} (Template)")
     * @return self The created template workflow
     */
    public function saveAsTemplate(?string $name = null): self
    {
        $template = $this->replicate();
        $template->name = $name ?? "{$this->name} (Template)";
        $template->is_template = true;
        $template->status = WorkflowStatus::ACTIVE;
        $template->save();

        // Clone steps
        foreach ($this->steps as $step) {
            $newStep = $step->replicate();
            $newStep->workflow_id = $template->id;
            $newStep->save();
        }

        return $template;
    }

    /**
     * Create a new workflow from this template.
     *
     * @param  string|null  $name  New workflow name (defaults to template name)
     * @return self The created workflow instance
     *
     * @throws \RuntimeException If called on a non-template workflow
     */
    public function instantiateFromTemplate(?string $name = null): self
    {
        if (! $this->is_template) {
            throw new \RuntimeException('Cannot instantiate from a non-template workflow');
        }

        $workflow = $this->replicate();
        $workflow->name = $name ?? $this->name;
        $workflow->is_template = false;
        $workflow->status = WorkflowStatus::DRAFT;
        $workflow->user_id = auth()->id();
        $workflow->save();

        // Clone steps
        foreach ($this->steps as $step) {
            $newStep = $step->replicate();
            $newStep->workflow_id = $workflow->id;
            $newStep->save();
        }

        return $workflow;
    }

    /**
     * Check if the workflow can be executed.
     *
     * @return bool True if workflow status allows execution
     */
    public function canExecute(): bool
    {
        return $this->status->canExecute();
    }
}
