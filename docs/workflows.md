# Workflows

Learn how to create and manage workflows effectively in ForgePulse.

## What is a Workflow?

A workflow is a sequence of automated steps that execute in order to accomplish a specific task. Each workflow consists of:

- **Name**: A descriptive name for the workflow
- **Description**: What the workflow does
- **Status**: Active, inactive, or draft
- **Steps**: The individual actions that make up the workflow
- **Context**: Data passed between steps

## Creating a Workflow

```php
use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Enums\WorkflowStatus;

$workflow = Workflow::create([
    'name' => 'User Onboarding',
    'description' => 'Automated user onboarding process',
    'status' => WorkflowStatus::ACTIVE,
    'created_by' => auth()->id(),
]);
```

## Workflow Statuses

- **Active**: Workflow can be executed
- **Inactive**: Workflow exists but cannot be executed
- **Draft**: Workflow is being built and tested

```php
// Change workflow status
$workflow->update(['status' => WorkflowStatus::INACTIVE]);

// Check status
if ($workflow->isActive()) {
    // Workflow can be executed
}
```

## Managing Workflows

### Listing Workflows

```php
// Get all active workflows
$workflows = Workflow::active()->get();

// Get workflows created by current user
$myWorkflows = Workflow::where('created_by', auth()->id())->get();

// Search workflows
$workflows = Workflow::where('name', 'like', '%onboarding%')->get();
```

### Updating Workflows

```php
$workflow->update([
    'name' => 'Enhanced User Onboarding',
    'description' => 'Updated onboarding process with new features',
]);
```

### Deleting Workflows

```php
// Soft delete (recommended)
$workflow->delete();

// Force delete (permanent)
$workflow->forceDelete();

// Restore soft-deleted workflow
$workflow->restore();
```

## Workflow Execution

### Execute a Workflow

```php
// Execute asynchronously (queued)
$execution = $workflow->execute([
    'user_id' => $user->id,
    'email' => $user->email,
]);

// Execute synchronously
$execution = $workflow->execute(
    ['user_id' => $user->id],
    async: false
);
```

### Execution Context

The context is data that flows through the workflow and is available to all steps:

```php
$execution = $workflow->execute([
    'user_id' => 123,
    'user_email' => 'user@example.com',
    'signup_date' => now(),
    'plan' => 'premium',
]);
```

Steps can access and modify the context:

```php
// In a step handler
public function handle(WorkflowStep $step, array $context): array
{
    $userId = $context['user_id'];
    
    // Add data to context
    $context['welcome_email_sent'] = true;
    $context['sent_at'] = now();
    
    return $context;
}
```

## Workflow Templates

Save workflows as templates for reuse:

```php
// Save as template
$template = $workflow->saveAsTemplate([
    'name' => 'Standard Onboarding Template',
    'description' => 'Reusable onboarding workflow',
    'category' => 'user-management',
]);

// Load from template
$newWorkflow = Workflow::createFromTemplate($template);
```

## Best Practices

### Naming Conventions

- Use clear, descriptive names
- Include the trigger or purpose
- Examples: "User Onboarding", "Order Fulfillment", "Daily Report Generation"

### Organization

- Group related workflows by category
- Use tags or custom fields for filtering
- Document workflow purpose and requirements

### Testing

```php
// Test workflow with sample data
$testExecution = $workflow->execute([
    'user_id' => 999999, // Test user
    'test_mode' => true,
], async: false);

// Check results
if ($testExecution->isCompleted()) {
    echo "Workflow test passed!";
}
```

### Error Handling

```php
// Configure error handling
$workflow->update([
    'error_handling' => [
        'on_step_failure' => 'continue', // or 'stop'
        'retry_failed_steps' => true,
        'max_retries' => 3,
        'notify_on_failure' => ['admin@example.com'],
    ],
]);
```

## Workflow Events

Listen to workflow events:

```php
use AlizHarb\ForgePulse\Events\WorkflowStarted;
use AlizHarb\ForgePulse\Events\WorkflowCompleted;
use AlizHarb\ForgePulse\Events\WorkflowFailed;

// In EventServiceProvider
protected $listen = [
    WorkflowStarted::class => [
        LogWorkflowStart::class,
    ],
    WorkflowCompleted::class => [
        SendCompletionNotification::class,
    ],
    WorkflowFailed::class => [
        AlertAdministrators::class,
    ],
];
```

## Performance Optimization

### Caching

```php
// Cache workflow definition
$workflow = Cache::remember(
    "workflow.{$id}",
    3600,
    fn() => Workflow::with('steps')->find($id)
);
```

### Queueing

```php
// Use specific queue for workflows
config(['forgepulse.execution.queue' => 'workflows']);

// Set queue priority
$execution = $workflow->execute($context, queue: 'high-priority');
```

## Next Steps

- Learn about [Steps](#steps) and step types
- Explore [Features](#features) like parallel execution
- Check out [Examples](#examples) for common workflows
