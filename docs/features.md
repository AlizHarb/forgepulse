# v1.1.0 Features

ForgePulse v1.1.0 introduces powerful new features for advanced workflow management.

## ⏱️ Timeout Orchestration

Prevent long-running operations by configuring step-level timeouts. If a step exceeds its timeout, it will be automatically terminated.

> **Requirements:** The `pcntl` PHP extension is required for timeout enforcement. The feature gracefully degrades if the extension is not available.

### Basic Usage

```php
use AlizHarb\ForgePulse\Models\WorkflowStep;

// Set timeout for a step (in seconds)
$step->update([
    'timeout' => 30, // 30 seconds
]);

// The step will be terminated if it runs longer than 30 seconds
```

### Example: API Call with Timeout

```php
$workflow = Workflow::create([
    'name' => 'External API Integration',
]);

$step = $workflow->steps()->create([
    'name' => 'Fetch User Data from API',
    'type' => StepType::WEBHOOK,
    'timeout' => 10, // 10 second timeout
    'configuration' => [
        'url' => 'https://api.example.com/users',
        'method' => 'GET',
    ],
]);
```

### How It Works

- Uses `pcntl_alarm()` to set a signal-based timeout
- Throws `StepTimeoutException` when timeout is exceeded
- Execution log is marked as failed with timeout message
- Workflow can continue or fail based on error handling

---

## ⏸️ Pause/Resume Workflows

Pause workflow executions mid-flow for manual intervention, approvals, or external dependencies. Resume when ready.

### Pausing an Execution

```php
use AlizHarb\ForgePulse\Models\WorkflowExecution;

$execution = WorkflowExecution::find(1);

// Pause with optional reason
$execution->pause('Waiting for manager approval');

// Check if paused
if ($execution->isPaused()) {
    echo "Paused at: " . $execution->paused_at;
    echo "Reason: " . $execution->pause_reason;
}
```

### Resuming an Execution

```php
// Resume the execution
$execution->resume();

// The workflow will continue from where it was paused
```

### Example: Approval Workflow

```php
// In your workflow handler
class ApprovalHandler
{
    public function handle(WorkflowStep $step, array $context): array
    {
        $execution = WorkflowExecution::find($context['execution_id']);
        
        // Pause for approval
        $execution->pause('Awaiting manager approval');
        
        // Send notification to manager
        Notification::send(
            User::find($context['manager_id']),
            new ApprovalRequiredNotification($execution)
        );
        
        return $context;
    }
}

// In your approval controller
public function approve(WorkflowExecution $execution)
{
    $execution->resume();
    
    return response()->json(['message' => 'Workflow resumed']);
}
```

### API Integration

```bash
# Pause via API
curl -X POST https://your-app.com/api/forgepulse/executions/1/pause \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"reason": "Manual review required"}'

# Resume via API
curl -X POST https://your-app.com/api/forgepulse/executions/1/resume \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🔄 Parallel Execution

Execute multiple workflow steps concurrently for improved performance. Group steps together to run in parallel.

### Basic Configuration

```php
// Configure steps to run in parallel
$step1 = $workflow->steps()->create([
    'name' => 'Send Welcome Email',
    'type' => StepType::NOTIFICATION,
    'execution_mode' => 'parallel',
    'parallel_group' => 'notifications',
    'configuration' => [
        'notification_class' => WelcomeEmail::class,
    ],
]);

$step2 = $workflow->steps()->create([
    'name' => 'Send SMS Notification',
    'type' => StepType::NOTIFICATION,
    'execution_mode' => 'parallel',
    'parallel_group' => 'notifications',
    'configuration' => [
        'notification_class' => WelcomeSMS::class,
    ],
]);

// Both steps will execute concurrently
```

### Example: Multi-Channel Notifications

```php
$workflow = Workflow::create([
    'name' => 'User Onboarding',
]);

// These will run in parallel
$parallelSteps = [
    ['name' => 'Email', 'class' => WelcomeEmail::class],
    ['name' => 'SMS', 'class' => WelcomeSMS::class],
    ['name' => 'Slack', 'class' => SlackNotification::class],
    ['name' => 'Webhook', 'class' => WebhookNotification::class],
];

foreach ($parallelSteps as $config) {
    $workflow->steps()->create([
        'name' => "Send {$config['name']}",
        'type' => StepType::NOTIFICATION,
        'execution_mode' => 'parallel',
        'parallel_group' => 'onboarding-notifications',
        'configuration' => [
            'notification_class' => $config['class'],
        ],
    ]);
}
```

### Performance Benefits

> **Example:** Instead of 4 notifications taking 4 seconds sequentially, they complete in ~1 second when run in parallel.

### Best Practices

- Use parallel execution for independent operations (notifications, API calls)
- Group related parallel steps with the same `parallel_group`
- Avoid parallel execution for steps that depend on each other
- Monitor resource usage when running many parallel steps

---

## 📅 Execution Scheduling

Schedule workflows to execute at a specific time in the future. Perfect for delayed tasks, reminders, and time-based automation.

### Schedule a Workflow

```php
use AlizHarb\ForgePulse\Models\Workflow;

$workflow = Workflow::find(1);

// Schedule for 2 hours from now
$execution = $workflow->execute([
    'scheduled_at' => now()->addHours(2),
    'context' => [
        'user_id' => 123,
        'action' => 'send_reminder',
    ],
]);
```

### Example: Reminder System

```php
class ReminderController
{
    public function scheduleReminder(Request $request)
    {
        $workflow = Workflow::where('name', 'Send Reminder')->first();
        
        $execution = $workflow->execute([
            'scheduled_at' => Carbon::parse($request->reminder_time),
            'context' => [
                'user_id' => $request->user_id,
                'message' => $request->message,
            ],
        ]);
        
        return response()->json([
            'message' => 'Reminder scheduled',
            'execution_id' => $execution->id,
            'scheduled_for' => $execution->scheduled_at,
        ]);
    }
}
```

### Example: Delayed Onboarding

```php
// Send follow-up email 24 hours after signup
$onboardingWorkflow = Workflow::where('name', 'Day 1 Follow-up')->first();

$onboardingWorkflow->execute([
    'scheduled_at' => now()->addDay(),
    'context' => [
        'user_id' => $newUser->id,
        'signup_date' => now(),
    ],
]);
```

### Recurring Schedules

```php
// Store recurring schedule configuration
$execution->update([
    'schedule_config' => [
        'frequency' => 'daily',
        'time' => '09:00',
        'timezone' => 'America/New_York',
    ],
]);
```

> **Note:** You'll need to set up a Laravel scheduler or cron job to check for and execute scheduled workflows.

---

## 🌐 REST API

ForgePulse provides a comprehensive REST API for mobile monitoring, integrations, and remote workflow management.

### Configuration

```php
// config/forgepulse.php
'api' => [
    'enabled' => true,
    'middleware' => ['api', 'auth:sanctum'],
    'rate_limit' => '60,1', // 60 requests per minute
],
```

### Available Endpoints

#### Workflows

```bash
# List all workflows
GET /api/forgepulse/workflows

# Get workflow details
GET /api/forgepulse/workflows/{id}

# Response example
{
  "id": 1,
  "name": "User Onboarding",
  "description": "Automated user onboarding process",
  "status": "active",
  "steps_count": 5,
  "executions_count": 142,
  "created_at": "2025-11-26T12:00:00.000Z"
}
```

#### Executions

```bash
# List all executions
GET /api/forgepulse/executions

# Get execution details
GET /api/forgepulse/executions/{id}

# Response example
{
  "id": 1,
  "workflow_id": 1,
  "status": "running",
  "is_paused": false,
  "duration": 45,
  "started_at": "2025-11-26T12:00:00.000Z",
  "logs": [...]
}
```

#### Pause/Resume

```bash
# Pause execution
POST /api/forgepulse/executions/{id}/pause
Content-Type: application/json

{
  "reason": "Manual review required"
}

# Resume execution
POST /api/forgepulse/executions/{id}/resume
```

### Authentication

```php
// Generate API token (Laravel Sanctum)
$token = $user->createToken('mobile-app')->plainTextToken;

// Use in requests
curl -H "Authorization: Bearer {$token}" \
  https://your-app.com/api/forgepulse/workflows
```

### Example: Mobile App Integration

```javascript
// React Native / Flutter example
async function fetchWorkflows() {
  const response = await fetch(
    'https://your-app.com/api/forgepulse/workflows',
    {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      }
    }
  );
  
  const data = await response.json();
  return data.data; // Array of workflows
}

async function pauseExecution(executionId, reason) {
  await fetch(
    `https://your-app.com/api/forgepulse/executions/${executionId}/pause`,
    {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ reason })
    }
  );
}
```

### Rate Limiting

> **Default:** 60 requests per minute. Configure in `config/forgepulse.php`.

### API Resources

All API responses use JSON:API-compliant resources:

- `WorkflowResource` - Workflow data with relationships
- `ExecutionResource` - Execution data with logs
- `StepResource` - Step configuration and status
- `LogResource` - Execution log entries
