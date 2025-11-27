# ForgePulse Example Demo Application

This example demonstrates how to use ForgePulse in a Laravel application.

## Setup

1. Create a new Laravel application:

```bash
laravel new forgepulse-demo
cd forgepulse-demo
```

2. Install ForgePulse:

```bash
composer require alizharb/forgepulse
```

3. Publish and run migrations:

```bash
php artisan vendor:publish --tag=forgepulse-migrations
php artisan migrate
```

## Example 1: User Onboarding Workflow

Create a workflow that sends welcome emails to new users:

```php
// routes/web.php
use AlizHarb\ForgePulse\Models\Workflow;
use Illuminate\Support\Facades\Route;

Route::get('/workflows/create-onboarding', function () {
    $workflow = Workflow::create([
        'name' => 'User Onboarding',
        'description' => 'Automated user onboarding process',
        'status' => 'active',
    ]);

    // Step 1: Send welcome email
    $workflow->steps()->create([
        'name' => 'Send Welcome Email',
        'type' => 'notification',
        'position' => 1,
        'x_position' => 100,
        'y_position' => 100,
        'configuration' => [
            'notification_class' => \App\Notifications\WelcomeEmail::class,
            'recipients' => ['{{user_id}}'],
        ],
    ]);

    // Step 2: Wait 24 hours
    $workflow->steps()->create([
        'name' => 'Wait 24 Hours',
        'type' => 'delay',
        'position' => 2,
        'x_position' => 100,
        'y_position' => 200,
        'configuration' => [
            'seconds' => 86400,
        ],
    ]);

    // Step 3: Send follow-up
    $workflow->steps()->create([
        'name' => 'Send Follow-up Email',
        'type' => 'notification',
        'position' => 3,
        'x_position' => 100,
        'y_position' => 300,
        'configuration' => [
            'notification_class' => \App\Notifications\FollowUpEmail::class,
            'recipients' => ['{{user_id}}'],
        ],
    ]);

    return redirect()->route('workflows.show', $workflow);
});
```

## Example 2: Order Processing Workflow

Create a workflow with conditional branching based on order total:

```php
Route::get('/workflows/create-order-processing', function () {
    $workflow = Workflow::create([
        'name' => 'Order Processing',
        'description' => 'Process orders with conditional logic',
        'status' => 'active',
    ]);

    // Step 1: Validate order
    $validateStep = $workflow->steps()->create([
        'name' => 'Validate Order',
        'type' => 'action',
        'position' => 1,
        'configuration' => [
            'action_class' => \App\Actions\ValidateOrder::class,
            'parameters' => ['order_id' => '{{order_id}}'],
        ],
    ]);

    // Step 2: High-value order notification (conditional)
    $workflow->steps()->create([
        'name' => 'Notify Manager (High Value)',
        'type' => 'notification',
        'position' => 2,
        'parent_step_id' => $validateStep->id,
        'configuration' => [
            'notification_class' => \App\Notifications\HighValueOrder::class,
            'recipients' => [1], // Manager user ID
        ],
        'conditions' => [
            'operator' => 'and',
            'rules' => [
                ['field' => 'order.total', 'operator' => '>', 'value' => 1000],
            ],
        ],
    ]);

    // Step 3: Standard processing
    $workflow->steps()->create([
        'name' => 'Process Payment',
        'type' => 'action',
        'position' => 3,
        'parent_step_id' => $validateStep->id,
        'configuration' => [
            'action_class' => \App\Actions\ProcessPayment::class,
            'parameters' => ['order_id' => '{{order_id}}'],
        ],
    ]);

    return redirect()->route('workflows.show', $workflow);
});
```

## Example 3: Workflow Builder UI

Create a route to display the visual workflow builder:

```php
// routes/web.php
Route::get('/workflows/{workflow}/builder', function (Workflow $workflow) {
    return view('workflows.builder', compact('workflow'));
});
```

```blade
{{-- resources/views/workflows/builder.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow Builder - {{ $workflow->name }}</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('vendor/forgepulse/css/forgepulse.css') }}">
</head>
<body>
    <livewire:forgepulse::workflow-builder :workflow="$workflow" />

    @livewireScripts
    <script src="{{ asset('vendor/forgepulse/js/workflow-designer.js') }}"></script>
</body>
</html>
```

## Example 4: Execute Workflow

Execute a workflow programmatically:

```php
// In your controller or job
use AlizHarb\ForgePulse\Models\Workflow;

$workflow = Workflow::find(1);

// Execute asynchronously (queued)
$execution = $workflow->execute([
    'user_id' => $user->id,
    'email' => $user->email,
    'name' => $user->name,
]);

// Execute synchronously
$execution = $workflow->execute([
    'user_id' => $user->id,
], async: false);
```

## Example 5: Monitor Execution

Display real-time execution tracking:

```php
// routes/web.php
Route::get('/workflows/executions/{execution}', function (WorkflowExecution $execution) {
    return view('workflows.execution', compact('execution'));
});
```

```blade
{{-- resources/views/workflows/execution.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow Execution</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('vendor/forgepulse/css/forgepulse.css') }}">
</head>
<body>
    <livewire:forgepulse::workflow-execution-tracker :execution="$execution" />

    @livewireScripts
</body>
</html>
```

## Example 6: Custom Action Handler

Create a custom action handler:

```php
// app/Actions/SendSlackNotification.php
namespace App\Actions;

class SendSlackNotification
{
    public function execute(array $parameters, array $context): array
    {
        $message = $parameters['message'] ?? 'Default message';
        $channel = $parameters['channel'] ?? '#general';

        // Send to Slack
        \Illuminate\Support\Facades\Http::post('https://hooks.slack.com/services/YOUR/WEBHOOK/URL', [
            'channel' => $channel,
            'text' => $message,
        ]);

        return [
            'slack_sent' => true,
            'channel' => $channel,
        ];
    }
}
```

Use it in a workflow step:

```php
$workflow->steps()->create([
    'name' => 'Send Slack Notification',
    'type' => 'action',
    'configuration' => [
        'action_class' => \App\Actions\SendSlackNotification::class,
        'parameters' => [
            'message' => 'New order received: {{order_id}}',
            'channel' => '#orders',
        ],
    ],
]);
```

## Example 7: Listen to Workflow Events

```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    \AlizHarb\ForgePulse\Events\WorkflowCompleted::class => [
        \App\Listeners\SendWorkflowCompletionNotification::class,
    ],
    \AlizHarb\ForgePulse\Events\WorkflowFailed::class => [
        \App\Listeners\AlertAdminOfWorkflowFailure::class,
    ],
];
```

```php
// app/Listeners/SendWorkflowCompletionNotification.php
namespace App\Listeners;

use AlizHarb\ForgePulse\Events\WorkflowCompleted;

class SendWorkflowCompletionNotification
{
    public function handle(WorkflowCompleted $event): void
    {
        $execution = $event->execution;

        // Send notification to workflow owner
        $execution->workflow->user->notify(
            new \AlizHarb\ForgePulse\Notifications\WorkflowCompletedNotification($execution)
        );
    }
}
```

## Running the Examples

1. Start your Laravel development server:

```bash
php artisan serve
```

2. Visit the routes:

- `/workflows/create-onboarding` - Create onboarding workflow
- `/workflows/create-order-processing` - Create order processing workflow
- `/workflows/{id}/builder` - Visual workflow builder
- `/workflows/executions/{id}` - Execution tracker

## Testing

Run the example tests:

```bash
php artisan test
```
