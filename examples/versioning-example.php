<?php

/**
 * ForgePulse v1.2.0 - Workflow Versioning Examples
 *
 * This file demonstrates the new workflow versioning features introduced in v1.2.0.
 */

require __DIR__.'/../vendor/autoload.php';

use AlizHarb\ForgePulse\Enums\StepType;
use AlizHarb\ForgePulse\Enums\WorkflowStatus;
use AlizHarb\ForgePulse\Models\Workflow;

// Example 1: Automatic Versioning
echo "\n=== Example 1: Automatic Versioning ===\n";

$workflow = Workflow::create([
    'name' => 'Customer Onboarding',
    'description' => 'Automated customer onboarding workflow',
    'status' => WorkflowStatus::DRAFT,
]);

// Add initial steps
$workflow->steps()->create([
    'name' => 'Send Welcome Email',
    'type' => StepType::NOTIFICATION,
    'position' => 1,
    'configuration' => [
        'notification_class' => 'App\\Notifications\\WelcomeEmail',
    ],
]);

// Save workflow - automatically creates version 1
$workflow->save();
echo "Created workflow with automatic version 1\n";

// Make changes
$workflow->steps()->create([
    'name' => 'Wait 24 Hours',
    'type' => StepType::DELAY,
    'position' => 2,
    'configuration' => ['seconds' => 86400],
]);

// Save again - automatically creates version 2
$workflow->save();
echo "Updated workflow, automatic version 2 created\n";

// Example 2: Manual Version Creation
echo "\n=== Example 2: Manual Version Creation ===\n";

// Create a version with description before making major changes
$version = $workflow->createVersion('Before adding premium features');
echo "Created manual version {$version->version_number}: {$version->description}\n";

// Make significant changes
$workflow->steps()->create([
    'name' => 'Check Premium Status',
    'type' => StepType::CONDITION,
    'position' => 3,
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            ['field' => 'user.subscription', 'operator' => '==', 'value' => 'premium'],
        ],
    ],
]);

$workflow->save();
echo "Made changes and saved (version {$workflow->latestVersion()->version_number})\n";

// Example 3: View Version History
echo "\n=== Example 3: View Version History ===\n";

$versions = $workflow->versions;
echo "Total versions: {$versions->count()}\n";

foreach ($versions as $version) {
    echo "  - Version {$version->version_number}: {$version->description}\n";
    echo "    Created: {$version->created_at->format('Y-m-d H:i:s')}\n";
    echo '    Steps: '.count($version->steps_snapshot)."\n";
}

// Example 4: Compare Versions
echo "\n=== Example 4: Compare Versions ===\n";

if ($versions->count() >= 2) {
    $latestVersion = $versions->first();
    $previousVersion = $versions->skip(1)->first();

    $diff = $latestVersion->compare($previousVersion);

    echo "Comparing version {$latestVersion->version_number} with version {$previousVersion->version_number}:\n";
    echo "  - Steps added: {$diff['steps_added']}\n";
    echo "  - Steps modified: {$diff['steps_modified']}\n";
    echo '  - Configuration changed: '.($diff['configuration_changed'] ? 'Yes' : 'No')."\n";
    echo "  - Time difference: {$diff['created_at_diff']}\n";
}

// Example 5: Rollback to Previous Version
echo "\n=== Example 5: Rollback to Previous Version ===\n";

if ($versions->count() >= 2) {
    $targetVersion = $versions->skip(1)->first();

    echo "Current steps: {$workflow->steps->count()}\n";
    echo "Rolling back to version {$targetVersion->version_number}...\n";

    $workflow->restoreVersion($targetVersion->id);
    $workflow->refresh();

    echo "Rollback complete!\n";
    echo "Steps after rollback: {$workflow->steps->count()}\n";
    echo "Latest version is now: {$workflow->latestVersion()->version_number}\n";
}

// Example 6: Version with Custom Description
echo "\n=== Example 6: Version with Custom Description ===\n";

$customVersion = $workflow->createVersion('Production release v2.1 - Added email verification');
echo "Created version {$customVersion->version_number} with custom description\n";
echo "Description: {$customVersion->description}\n";

// Example 7: Check Latest Version
echo "\n=== Example 7: Check Latest Version ===\n";

$latest = $workflow->latestVersion();
if ($latest) {
    echo "Latest version: {$latest->version_number}\n";
    echo "Created at: {$latest->created_at->format('Y-m-d H:i:s')}\n";
    echo "Description: {$latest->description}\n";

    if ($latest->restored_at) {
        echo "This version was restored at: {$latest->restored_at->format('Y-m-d H:i:s')}\n";
    }
}

echo "\n=== Versioning Examples Complete ===\n\n";
