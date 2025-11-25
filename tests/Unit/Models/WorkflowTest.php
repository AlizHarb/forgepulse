<?php

use AlizHarb\FlowForge\Models\Workflow;
use AlizHarb\FlowForge\Models\WorkflowStep;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a workflow', function () {
    $workflow = Workflow::factory()->create([
        'name' => 'Test Workflow',
        'status' => 'active',
    ]);

    expect($workflow->name)->toBe('Test Workflow')
        ->and($workflow->status->value)->toBe('active');
});

it('has steps relationship', function () {
    $workflow = Workflow::factory()->create();
    $step = WorkflowStep::factory()->create(['workflow_id' => $workflow->id]);

    expect($workflow->steps)->toHaveCount(1)
        ->and($workflow->steps->first()->id)->toBe($step->id);
});

it('can execute a workflow', function () {
    $workflow = Workflow::factory()->create(['status' => 'active']);
    WorkflowStep::factory()->create([
        'workflow_id' => $workflow->id,
        'type' => 'delay',
        'configuration' => ['seconds' => 0],
    ]);

    $execution = $workflow->execute(['test' => 'data']);

    expect($execution)->toBeInstanceOf(\AlizHarb\FlowForge\Models\WorkflowExecution::class)
        ->and($execution->status->value)->toBe('pending')
        ->and($execution->context->getArrayCopy())->toBe(['test' => 'data']);
});

it('can save workflow as template', function () {
    $workflow = Workflow::factory()->create(['name' => 'Original']);
    WorkflowStep::factory()->create(['workflow_id' => $workflow->id]);

    $template = $workflow->saveAsTemplate('Template Name');

    expect($template->is_template)->toBeTrue()
        ->and($template->name)->toBe('Template Name')
        ->and($template->steps)->toHaveCount(1);
});

it('can instantiate from template', function () {
    $template = Workflow::factory()->create(['is_template' => true]);
    WorkflowStep::factory()->create(['workflow_id' => $template->id]);

    $workflow = $template->instantiateFromTemplate('New Workflow');

    expect($workflow->is_template)->toBeFalse()
        ->and($workflow->name)->toBe('New Workflow')
        ->and($workflow->steps)->toHaveCount(1);
});

it('validates workflow structure', function () {
    $workflow = Workflow::factory()->create();

    expect(fn () => $workflow->validate())->toThrow(\InvalidArgumentException::class);
});

it('scopes active workflows', function () {
    Workflow::factory()->create(['status' => 'active']);
    Workflow::factory()->create(['status' => 'inactive']);

    expect(Workflow::active()->count())->toBe(1);
});

it('scopes template workflows', function () {
    Workflow::factory()->create(['is_template' => true]);
    Workflow::factory()->create(['is_template' => false]);

    expect(Workflow::templates()->count())->toBe(1);
});
