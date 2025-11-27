<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Workflow Version Model
 *
 * Represents a snapshot of a workflow at a specific point in time.
 * Enables version history tracking and rollback functionality.
 *
 * @property int $id
 * @property int $workflow_id
 * @property int $version_number
 * @property string $name
 * @property string|null $description
 * @property array<string, mixed>|null $configuration
 * @property array<int, array<string, mixed>> $steps_snapshot
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $restored_at
 * @property-read Workflow $workflow
 * @property-read \Illuminate\Database\Eloquent\Model|null $creator
 */
class WorkflowVersion extends Model
{
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'workflow_id',
        'version_number',
        'name',
        'description',
        'configuration',
        'steps_snapshot',
        'created_by',
        'restored_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'configuration' => 'array',
            'steps_snapshot' => 'array',
            'created_at' => 'datetime',
            'restored_at' => 'datetime',
        ];
    }

    /**
     * Get the workflow that owns this version.
     *
     * @return BelongsTo<Workflow, $this>
     */
    public function workflow(): BelongsTo
    {
        /** @var BelongsTo<Workflow, $this> */
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the user who created this version.
     *
     * @return BelongsTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function creator(): BelongsTo
    {
        /** @var class-string<\Illuminate\Database\Eloquent\Model> $model */
        $model = config('auth.providers.users.model', 'App\\Models\\User');

        /** @var BelongsTo<\Illuminate\Database\Eloquent\Model, $this> */
        return $this->belongsTo($model, 'created_by');
    }

    /**
     * Restore the workflow to this version.
     *
     * @return bool True if restoration was successful
     */
    public function restore(): bool
    {
        $workflow = $this->workflow;

        // Create a new version before restoring (backup current state)
        $workflow->createVersion('Before restoring to version '.$this->version_number);

        // Restore workflow configuration
        if ($this->configuration) {
            /** @var array<string, mixed> $config */
            $config = $this->configuration;
            $workflow->configuration = new \ArrayObject($config);
        }

        $workflow->save();

        // Delete all current steps
        $workflow->steps()->delete();

        // Restore steps from snapshot
        foreach ($this->steps_snapshot as $stepData) {
            $workflow->steps()->create([
                'name' => $stepData['name'],
                'type' => $stepData['type'],
                'configuration' => $stepData['configuration'] ?? [],
                'conditions' => $stepData['conditions'] ?? [],
                'position' => $stepData['position'],
                'x_position' => $stepData['x_position'] ?? 100,
                'y_position' => $stepData['y_position'] ?? 100,
                'parent_step_id' => $stepData['parent_step_id'] ?? null,
                'is_enabled' => $stepData['is_enabled'] ?? true,
                'timeout' => $stepData['timeout'] ?? null,
                'parallel_group' => $stepData['parallel_group'] ?? null,
            ]);
        }

        // Mark this version as restored
        $this->update(['restored_at' => now()]);

        return true;
    }

    /**
     * Compare this version with another version.
     *
     * @param  WorkflowVersion  $otherVersion  Version to compare with
     * @return array<string, mixed> Differences between versions
     */
    public function compare(WorkflowVersion $otherVersion): array
    {
        return [
            'version_numbers' => [
                'this' => $this->version_number,
                'other' => $otherVersion->version_number,
            ],
            'configuration_changed' => $this->configuration !== $otherVersion->configuration,
            'steps_added' => count($this->steps_snapshot) - count($otherVersion->steps_snapshot),
            'steps_modified' => $this->getModifiedStepsCount($otherVersion),
            'created_at_diff' => $this->created_at->diffForHumans($otherVersion->created_at),
        ];
    }

    /**
     * Get count of modified steps between versions.
     *
     * @param  WorkflowVersion  $otherVersion  Version to compare with
     * @return int Number of modified steps
     */
    protected function getModifiedStepsCount(WorkflowVersion $otherVersion): int
    {
        $thisSteps = collect($this->steps_snapshot)->keyBy('id');
        $otherSteps = collect($otherVersion->steps_snapshot)->keyBy('id');

        $modified = 0;

        foreach ($thisSteps as $id => $step) {
            if ($otherSteps->has($id) && $step !== $otherSteps->get($id)) {
                $modified++;
            }
        }

        return $modified;
    }
}
