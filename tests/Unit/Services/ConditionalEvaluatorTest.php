<?php

use AlizHarb\ForgePulse\Services\ConditionalEvaluator;

it('evaluates simple equality conditions', function () {
    $evaluator = new ConditionalEvaluator();

    $conditions = ['name' => 'John'];
    $context = ['name' => 'John'];

    expect($evaluator->evaluate($conditions, $context))->toBeTrue();
});

it('evaluates complex condition groups', function () {
    $evaluator = new ConditionalEvaluator();

    $conditions = [
        'operator' => 'and',
        'rules' => [
            ['field' => 'age', 'operator' => '>', 'value' => 18],
            ['field' => 'status', 'operator' => '==', 'value' => 'active'],
        ],
    ];

    $context = ['age' => 25, 'status' => 'active'];

    expect($evaluator->evaluate($conditions, $context))->toBeTrue();
});

it('evaluates OR conditions', function () {
    $evaluator = new ConditionalEvaluator();

    $conditions = [
        'operator' => 'or',
        'rules' => [
            ['field' => 'role', 'operator' => '==', 'value' => 'admin'],
            ['field' => 'role', 'operator' => '==', 'value' => 'manager'],
        ],
    ];

    $context = ['role' => 'manager'];

    expect($evaluator->evaluate($conditions, $context))->toBeTrue();
});

it('supports various operators', function () {
    $evaluator = new ConditionalEvaluator();

    $tests = [
        [['field' => 'value', 'operator' => '>', 'value' => 5], ['value' => 10], true],
        [['field' => 'value', 'operator' => '<', 'value' => 5], ['value' => 3], true],
        [['field' => 'value', 'operator' => '>=', 'value' => 5], ['value' => 5], true],
        [['field' => 'value', 'operator' => 'contains', 'value' => 'test'], ['value' => 'this is a test'], true],
        [['field' => 'value', 'operator' => 'in', 'value' => [1, 2, 3]], ['value' => 2], true],
    ];

    foreach ($tests as [$rule, $context, $expected]) {
        $conditions = ['operator' => 'and', 'rules' => [$rule]];
        expect($evaluator->evaluate($conditions, $context))->toBe($expected);
    }
});
