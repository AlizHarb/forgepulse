# Quick Start

Create your first workflow in under 5 minutes.

## 1. Create a Workflow

```php
use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Enums\WorkflowStatus;

$workflow = Workflow::create([
    'name' => 'User Onboarding',
    'description' => 'Automated user onboarding process',
    'status' => WorkflowStatus::ACTIVE,
]);
```

## 2. Add Steps

```php
use AlizHarb\ForgePulse\Enums\StepType;

// Send welcome email
$workflow->steps()->create([
    'name' => 'Send Welcome Email',
    'type' => StepType::NOTIFICATION,
    'position' => 1,
    'configuration' => [
        'notification_class' => \App\Notifications\WelcomeEmail::class,
        'recipients' => ['{{user_id}}'],
    ],
]);

// Wait 24 hours
$workflow->steps()->create([
    'name' => 'Wait 24 Hours',
    'type' => StepType::DELAY,
    'position' => 2,
    'configuration' => [
        'seconds' => 86400,
    ],
]);

// Send follow-up email
$workflow->steps()->create([
    'name' => 'Send Follow-up Email',
    'type' => StepType::NOTIFICATION,
    'position' => 3,
    'configuration' => [
        'notification_class' => \App\Notifications\FollowUpEmail::class,
        'recipients' => ['{{user_id}}'],
    ],
]);
```

## 3. Execute the Workflow

```php
// Execute asynchronously (queued)
$execution = $workflow->execute([
    'user_id' => $user->id,
    'email' => $user->email,
]);

// Execute synchronously
$execution = $workflow->execute(['user_id' => $user->id], async: false);
```

## 4. Use the Visual Builder

Add the workflow builder component to your Blade view:

```blade
<livewire:forgepulse::workflow-builder :workflow="$workflow" />
```

This provides a drag-and-drop interface for building and editing workflows visually.

## 5. Monitor Execution

Track workflow execution in real-time:

```php
use AlizHarb\ForgePulse\Models\WorkflowExecution;

$execution = WorkflowExecution::find($executionId);

// Check status
echo $execution->status; // pending, running, completed, failed

// Get logs
foreach ($execution->logs as $log) {
    echo "{$log->step->name}: {$log->status}\n";
}

// Check if completed
if ($execution->isCompleted()) {
    echo "Workflow completed successfully!";
}
```

## What's Next?

- Learn about [Workflows](#workflows) and how to organize them
- Explore different [Step Types](#step-types)
- Understand [Conditional Logic](#conditional-logic)
- Check out [Advanced Features](#advanced-features)
