<?php

/**
 * ForgePulse v1.2.0 - Advanced Conditional Operators Examples
 *
 * This file demonstrates the new advanced conditional operators introduced in v1.2.0.
 */

require __DIR__.'/../vendor/autoload.php';

use AlizHarb\ForgePulse\Enums\StepType;
use AlizHarb\ForgePulse\Enums\WorkflowStatus;
use AlizHarb\ForgePulse\Models\Workflow;

echo "\n=== ForgePulse v1.2.0 - Advanced Conditional Operators ===\n\n";

$workflow = Workflow::create([
    'name' => 'Advanced Conditions Demo',
    'description' => 'Demonstrating v1.2.0 conditional operators',
    'status' => WorkflowStatus::ACTIVE,
]);

// Example 1: Regex Pattern Matching
echo "1. Regex Pattern Matching\n";
$step1 = $workflow->steps()->create([
    'name' => 'Validate Email Domain',
    'type' => StepType::CONDITION,
    'position' => 1,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.email',
                'operator' => 'regex',
                'value' => '/^[a-z0-9._%+-]+@company\\.com$/i',
            ],
        ],
    ],
]);
echo "   Created: Email must match company domain pattern\n\n";

// Example 2: Range Checks (Between)
echo "2. Range Checks\n";
$step2 = $workflow->steps()->create([
    'name' => 'Check Age Range',
    'type' => StepType::CONDITION,
    'position' => 2,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.age',
                'operator' => 'between',
                'value' => [18, 65],
            ],
        ],
    ],
]);
echo "   Created: Age must be between 18 and 65\n\n";

// Example 3: Array Membership (in_array)
echo "3. Array Membership\n";
$step3 = $workflow->steps()->create([
    'name' => 'Check User Roles',
    'type' => StepType::CONDITION,
    'position' => 3,
    'conditions' => [
        'operator' => 'or',
        'rules' => [
            [
                'field' => 'user.roles',
                'operator' => 'in_array',
                'value' => 'admin',
            ],
            [
                'field' => 'user.roles',
                'operator' => 'in_array',
                'value' => 'moderator',
            ],
        ],
    ],
]);
echo "   Created: User must have admin or moderator role\n\n";

// Example 4: Array Subset Checks (contains_all)
echo "4. Array Subset Checks\n";
$step4 = $workflow->steps()->create([
    'name' => 'Check Required Permissions',
    'type' => StepType::CONDITION,
    'position' => 4,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.permissions',
                'operator' => 'contains_all',
                'value' => ['read', 'write', 'delete'],
            ],
        ],
    ],
]);
echo "   Created: User must have all required permissions\n\n";

// Example 5: Array Intersection (contains_any)
echo "5. Array Intersection\n";
$step5 = $workflow->steps()->create([
    'name' => 'Check Any Premium Feature',
    'type' => StepType::CONDITION,
    'position' => 5,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.features',
                'operator' => 'contains_any',
                'value' => ['premium_support', 'priority_queue', 'advanced_analytics'],
            ],
        ],
    ],
]);
echo "   Created: User must have at least one premium feature\n\n";

// Example 6: Length Comparisons
echo "6. Length Comparisons\n";
$step6 = $workflow->steps()->create([
    'name' => 'Validate Description Length',
    'type' => StepType::CONDITION,
    'position' => 6,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'product.description',
                'operator' => 'length_gt',
                'value' => 50,
            ],
            [
                'field' => 'product.description',
                'operator' => 'length_lt',
                'value' => 500,
            ],
        ],
    ],
]);
echo "   Created: Description must be between 50 and 500 characters\n\n";

// Example 7: Complex Nested Conditions
echo "7. Complex Nested Conditions\n";
$step7 = $workflow->steps()->create([
    'name' => 'Premium User Validation',
    'type' => StepType::CONDITION,
    'position' => 7,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.email',
                'operator' => 'regex',
                'value' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$/i',
            ],
            [
                'field' => 'user.subscription_amount',
                'operator' => 'between',
                'value' => [50, 500],
            ],
            [
                'field' => 'user.permissions',
                'operator' => 'contains_all',
                'value' => ['api_access', 'export_data'],
            ],
            [
                'operator' => 'or',
                'rules' => [
                    [
                        'field' => 'user.tags',
                        'operator' => 'in_array',
                        'value' => 'vip',
                    ],
                    [
                        'field' => 'user.referrals',
                        'operator' => 'length_gt',
                        'value' => 5,
                    ],
                ],
            ],
        ],
    ],
]);
echo "   Created: Complex validation with nested conditions\n\n";

// Example 8: Not Operators
echo "8. Negative Operators\n";
$step8 = $workflow->steps()->create([
    'name' => 'Exclude Test Users',
    'type' => StepType::CONDITION,
    'position' => 8,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            [
                'field' => 'user.email',
                'operator' => 'not_regex',
                'value' => '/^test.*@/',
            ],
            [
                'field' => 'user.age',
                'operator' => 'not_between',
                'value' => [0, 17],
            ],
            [
                'field' => 'user.roles',
                'operator' => 'not_in_array',
                'value' => 'banned',
            ],
        ],
    ],
]);
echo "   Created: Exclude test users, minors, and banned users\n\n";

// Summary
echo "=== Summary ===\n";
echo "Created {$workflow->steps->count()} conditional steps demonstrating:\n";
echo "  - Regex pattern matching (regex, not_regex)\n";
echo "  - Range checks (between, not_between)\n";
echo "  - Array membership (in_array, not_in_array)\n";
echo "  - Array subset checks (contains_all, contains_any)\n";
echo "  - Length comparisons (length_eq, length_gt, length_lt)\n";
echo "  - Complex nested conditions\n";
echo "\nAll operators are now available in your workflows!\n\n";
