# Workflow Steps

Steps are the building blocks of workflows. Each step performs a specific action and can pass data to the next step.

## Step Types

ForgePulse supports 7 different step types:

### Action Step

Execute custom PHP code or call a class method.

```php
$workflow->steps()->create([
    'name' => 'Process User Data',
    'type' => StepType::ACTION,
    'position' => 1,
    'configuration' => [
        'class' => \App\Actions\ProcessUserData::class,
        'method' => 'handle',
        'parameters' => ['user_id' => '{{user_id}}'],
    ],
]);
```

### Condition Step

Branch workflow execution based on conditions.

```php
$workflow->steps()->create([
    'name' => 'Check Premium User',
    'type' => StepType::CONDITION,
    'position' => 2,
    'configuration' => [
        'condition' => '{{user.plan}} === "premium"',
        'on_true' => 'send_premium_welcome',
        'on_false' => 'send_standard_welcome',
    ],
]);
```

### Delay Step

Wait for a specified duration before continuing.

```php
$workflow->steps()->create([
    'name' => 'Wait 24 Hours',
    'type' => StepType::DELAY,
    'position' => 3,
    'configuration' => [
        'seconds' => 86400, // 24 hours
    ],
]);
```

### Notification Step

Send notifications via email, SMS, Slack, etc.

```php
$workflow->steps()->create([
    'name' => 'Send Welcome Email',
    'type' => StepType::NOTIFICATION,
    'position' => 4,
    'configuration' => [
        'notification_class' => \App\Notifications\WelcomeEmail::class,
        'recipients' => ['{{user_email}}'],
        'data' => [
            'user_name' => '{{user_name}}',
            'signup_date' => '{{signup_date}}',
        ],
    ],
]);
```

### Webhook Step

Make HTTP requests to external APIs.

```php
$workflow->steps()->create([
    'name' => 'Notify CRM',
    'type' => StepType::WEBHOOK,
    'position' => 5,
    'configuration' => [
        'url' => 'https://crm.example.com/api/users',
        'method' => 'POST',
        'headers' => [
            'Authorization' => 'Bearer {{api_token}}',
            'Content-Type' => 'application/json',
        ],
        'body' => [
            'user_id' => '{{user_id}}',
            'email' => '{{user_email}}',
        ],
    ],
]);
```

### Event Step

Dispatch Laravel events.

```php
$workflow->steps()->create([
    'name' => 'Dispatch User Registered Event',
    'type' => StepType::EVENT,
    'position' => 6,
    'configuration' => [
        'event_class' => \App\Events\UserRegistered::class,
        'data' => [
            'user_id' => '{{user_id}}',
        ],
    ],
]);
```

### Job Step

Dispatch Laravel jobs to the queue.

```php
$workflow->steps()->create([
    'name' => 'Process User Avatar',
    'type' => StepType::JOB,
    'position' => 7,
    'configuration' => [
        'job_class' => \App\Jobs\ProcessUserAvatar::class,
        'queue' => 'images',
        'data' => [
            'user_id' => '{{user_id}}',
            'avatar_url' => '{{avatar_url}}',
        ],
    ],
]);
```

## Step Configuration

### Common Properties

All steps share these properties:

```php
[
    'name' => 'Step Name',           // Required: Display name
    'type' => StepType::ACTION,      // Required: Step type
    'position' => 1,                 // Required: Execution order
    'description' => 'What it does', // Optional: Documentation
    'timeout' => 30,                 // Optional: Max execution time (seconds)
    'retry_on_failure' => true,      // Optional: Retry if step fails
    'max_retries' => 3,              // Optional: Maximum retry attempts
    'configuration' => [],           // Required: Step-specific config
]
```

### Variable Interpolation

Use `{{variable}}` syntax to access context data:

```php
'configuration' => [
    'message' => 'Hello {{user_name}}, welcome to {{app_name}}!',
    'user_id' => '{{user_id}}',
    'nested_value' => '{{user.profile.company}}',
]
```

## Step Execution

### Execution Order

Steps execute in order by their `position` value:

```php
// These will execute in order: 1, 2, 3
$workflow->steps()->create(['position' => 1, ...]);
$workflow->steps()->create(['position' => 2, ...]);
$workflow->steps()->create(['position' => 3, ...]);
```

### Conditional Execution

Skip steps based on conditions:

```php
$workflow->steps()->create([
    'name' => 'Send Premium Features Email',
    'type' => StepType::NOTIFICATION,
    'position' => 5,
    'condition' => '{{user.plan}} === "premium"', // Only runs if true
    'configuration' => [...],
]);
```

### Error Handling

Configure how steps handle errors:

```php
$workflow->steps()->create([
    'name' => 'External API Call',
    'type' => StepType::WEBHOOK,
    'position' => 3,
    'retry_on_failure' => true,
    'max_retries' => 3,
    'retry_delay' => 60, // Wait 60 seconds between retries
    'on_failure' => 'continue', // or 'stop'
    'configuration' => [...],
]);
```

## Step Handlers

Create custom step handlers for complex logic:

```php
namespace App\WorkflowHandlers;

use AlizHarb\ForgePulse\Contracts\StepHandler;
use AlizHarb\ForgePulse\Models\WorkflowStep;

class CustomStepHandler implements StepHandler
{
    public function handle(WorkflowStep $step, array $context): array
    {
        // Your custom logic here
        $userId = $context['user_id'];
        
        // Perform actions
        $result = $this->doSomething($userId);
        
        // Update context
        $context['custom_result'] = $result;
        $context['processed_at'] = now();
        
        return $context;
    }
    
    public function validate(array $configuration): bool
    {
        // Validate step configuration
        return isset($configuration['required_field']);
    }
}
```

Register your handler:

```php
// In a service provider
use AlizHarb\ForgePulse\Facades\ForgePulse;

ForgePulse::registerStepHandler('custom', CustomStepHandler::class);
```

Use it in a workflow:

```php
$workflow->steps()->create([
    'name' => 'Custom Processing',
    'type' => 'custom',
    'position' => 1,
    'configuration' => [
        'required_field' => 'value',
    ],
]);
```

## Step Dependencies

Define dependencies between steps:

```php
$step1 = $workflow->steps()->create([...]);
$step2 = $workflow->steps()->create([...]);

// Step 2 depends on Step 1
$step2->dependencies()->attach($step1->id);
```

## Best Practices

### Keep Steps Focused

Each step should do one thing well:

```php
// Good: Focused steps
$workflow->steps()->create(['name' => 'Send Email', ...]);
$workflow->steps()->create(['name' => 'Update CRM', ...]);

// Bad: Too much in one step
$workflow->steps()->create(['name' => 'Send Email and Update CRM and Log Activity', ...]);
```

### Use Descriptive Names

```php
// Good
'name' => 'Send Welcome Email to New User'

// Bad
'name' => 'Step 1'
```

### Handle Errors Gracefully

```php
$workflow->steps()->create([
    'name' => 'Optional External API Call',
    'type' => StepType::WEBHOOK,
    'retry_on_failure' => true,
    'max_retries' => 2,
    'on_failure' => 'continue', // Don't stop workflow if this fails
    'configuration' => [...],
]);
```

### Test Steps Individually

```php
// Test a single step
$step = WorkflowStep::find(1);
$context = ['user_id' => 999999];

$result = $step->execute($context);
```

## Next Steps

- Learn about [Conditional Logic](#conditional-logic)
- Explore [Features](#features) like parallel execution
- Check out [Examples](#examples) for common patterns
