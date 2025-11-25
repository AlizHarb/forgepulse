<?php

use AlizHarb\FlowForge\Models\Workflow;
use AlizHarb\FlowForge\Models\WorkflowExecution;
use AlizHarb\FlowForge\Models\WorkflowStep;
use AlizHarb\FlowForge\Services\WorkflowEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('executes a simple workflow', function () {
    $workflow = Workflow::factory()->create(['status' => 'active']);

    $step = WorkflowStep::factory()->create([
        'workflow_id' => $workflow->id,
        'type' => 'delay',
        'configuration' => ['seconds' => 1],
    ]);

    $execution = WorkflowExecution::create([
        'workflow_id' => $workflow->id,
        'status' => 'pending',
        'context' => [],
    ]);

    $engine = app(WorkflowEngine::class);
    $engine->execute($execution);

    $execution->refresh();

    expect($execution->status->value)->toBe('completed')
        ->and($execution->started_at)->not->toBeNull()
        ->and($execution->completed_at)->not->toBeNull();
});

it('handles workflow execution failures', function () {
    $workflow = Workflow::factory()->create();

    $step = WorkflowStep::factory()->create([
        'workflow_id' => $workflow->id,
        'type' => 'action',
        'configuration' => ['action_class' => 'NonExistentClass'],
    ]);

    $execution = WorkflowExecution::create([
        'workflow_id' => $workflow->id,
        'status' => 'pending',
    ]);

    $engine = app(WorkflowEngine::class);

    // Should not throw exception anymore, but handle it gracefully
    $engine->execute($execution);

    $execution->refresh();
    expect($execution->status->value)->toBe('failed');
});

it('executes steps in correct order', function () {
    $workflow = Workflow::factory()->create();

    $step1 = WorkflowStep::factory()->create([
        'workflow_id' => $workflow->id,
        'position' => 1,
        'type' => 'delay',
        'configuration' => ['seconds' => 0],
    ]);

    $step2 = WorkflowStep::factory()->create([
        'workflow_id' => $workflow->id,
        'position' => 2,
        'parent_step_id' => $step1->id,
        'type' => 'delay',
        'configuration' => ['seconds' => 0],
    ]);

    $execution = WorkflowExecution::create([
        'workflow_id' => $workflow->id,
        'status' => 'pending',
    ]);

    $engine = app(WorkflowEngine::class);
    $engine->execute($execution);

    $logs = $execution->logs()->orderBy('created_at')->get();

    expect($logs)->toHaveCount(2)
        ->and($logs[0]->workflow_step_id)->toBe($step1->id)
        ->and($logs[1]->workflow_step_id)->toBe($step2->id);
});

it('skips steps with unmet conditions', function () {
    $workflow = Workflow::factory()->create();

    $step = WorkflowStep::factory()->create([
        'workflow_id' => $workflow->id,
        'type' => 'delay',
        'configuration' => ['seconds' => 0],
        'conditions' => [
            'operator' => 'and',
            'rules' => [
                ['field' => 'should_run', 'operator' => '==', 'value' => true],
            ],
        ],
    ]);

    $execution = WorkflowExecution::create([
        'workflow_id' => $workflow->id,
        'status' => 'pending',
        'context' => ['should_run' => false], // Condition will fail
    ]);

    $engine = app(WorkflowEngine::class);
    $engine->execute($execution);

    $log = $execution->logs()->first();

    expect($log->status->value)->toBe('skipped');
});
