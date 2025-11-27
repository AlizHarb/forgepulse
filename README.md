# ForgePulse

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alizharb/forgepulse.svg?style=flat-square)](https://packagist.org/packages/alizharb/forgepulse)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alizharb/forgepulse/tests.yml?label=tests)](https://github.com/alizharb/forgepulse/actions?query=workflow%3Atests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alizharb/forgepulse.svg?style=flat-square)](https://packagist.org/packages/alizharb/forgepulse)
[![License](https://img.shields.io/packagist/l/alizharb/forgepulse.svg?style=flat-square)](https://packagist.org/packages/alizharb/forgepulse)

**ForgePulse** is a powerful, production-ready Laravel package for building dynamic workflows with a drag-and-drop interface, conditional branching, and real-time execution tracking.

> **Note**: This project was formerly known as FlowForge. It has been renamed to ForgePulse.

## ✨ Features

- 🎨 **Drag-and-Drop Workflow Designer** - Visual workflow builder using Livewire 4 and Alpine.js
- 🔀 **Conditional Branching** - Complex if/else rules with 15+ comparison operators
- ⏱️ **Timeout Orchestration** - Configure step timeouts with automatic termination (v1.1.0)
- ⏸️ **Pause/Resume Workflows** - Pause and resume executions mid-flow (v1.1.0)
- 🔄 **Parallel Execution** - Execute multiple steps concurrently (v1.1.0)
- 📅 **Execution Scheduling** - Schedule workflows for future execution (v1.1.0)
- 🌐 **REST API** - Full API for mobile monitoring and integrations (v1.1.0)
- 📋 **Workflow Templates** - Save, load, and reuse workflow configurations
- ⚡ **Real-Time Execution Tracking** - Live monitoring with Livewire reactivity
- 🔗 **Laravel 12 Integration** - Seamless integration with events, jobs, and notifications
- 🔐 **Role-Based Access Control** - Granular permissions for workflow actions
- 🎯 **7 Step Types** - Actions, conditions, delays, notifications, webhooks, events, and jobs
- 📊 **Execution Logging** - Detailed step-by-step execution logs with performance metrics
- 🌍 **Multi-Language** - Built-in support for English, Spanish, French, German, and Arabic
- 🌙 **Dark Mode Support** - Beautiful UI with automatic dark mode
- 🚀 **PHP 8.3+ & Laravel 12** - Modern codebase with enums, readonly properties, and attributes
- ✅ **Fully Tested** - Comprehensive test suite with Pest 3

## 📋 Requirements

- PHP 8.3, 8.4, or 8.5
- Laravel 12
- Livewire 4

## 📦 Installation

Install the package via Composer:

```bash
composer require alizharb/forgepulse
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=forgepulse-config
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag=forgepulse-migrations
php artisan migrate
```

Optionally, publish the views and assets:

```bash
php artisan vendor:publish --tag=forgepulse-views
php artisan vendor:publish --tag=forgepulse-assets
php artisan vendor:publish --tag=forgepulse-lang
```

### Asset Inclusion

To ensure the ForgePulse UI renders correctly, you must include the package's CSS file in your application's layout (usually `resources/views/layouts/app.blade.php`):

```blade
<link href="{{ asset('vendor/forgepulse/css/forgepulse.css') }}" rel="stylesheet">
```

## 🚀 Quick Start

### 1. Create a Workflow

```php
use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Enums\WorkflowStatus;

$workflow = Workflow::create([
    'name' => 'User Onboarding',
    'description' => 'Automated user onboarding process',
    'status' => WorkflowStatus::ACTIVE,
]);
```

### 2. Add Workflow Steps

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

// Wait 1 day
$workflow->steps()->create([
    'name' => 'Wait 24 Hours',
    'type' => StepType::DELAY,
    'position' => 2,
    'configuration' => [
        'seconds' => 86400,
    ],
]);
```

### 3. Execute the Workflow

```php
// Execute asynchronously (queued)
$execution = $workflow->execute([
    'user_id' => $user->id,
    'email' => $user->email,
]);

// Execute synchronously
$execution = $workflow->execute(['user_id' => $user->id], async: false);
```

### 4. Use the Visual Builder

Include the Livewire component in your Blade view:

```blade
<livewire:forgepulse::workflow-builder :workflow="$workflow" />
```

## 📚 Documentation

For comprehensive documentation, visit our [interactive documentation site](https://alizharb.github.io/forgepulse/) or check the `docs/` directory.

### Key Topics

- [Installation Guide](docs/installation.html)
- [Quick Start Tutorial](docs/quick-start.html)
- [API Reference](docs/api-reference.html)
- [Interactive Examples](docs/examples.html)
- [Configuration Reference](docs/configuration.html)

## 🎯 Step Types

ForgePulse supports 7 step types out of the box:

### Action Step

Execute custom action classes:

```php
use AlizHarb\ForgePulse\Enums\StepType;

$step = $workflow->steps()->create([
    'type' => StepType::ACTION,
    'configuration' => [
        'action_class' => \App\Actions\ProcessUserData::class,
        'parameters' => ['user_id' => '{{user_id}}'],
    ],
]);
```

### Notification Step

Send Laravel notifications:

```php
$step = $workflow->steps()->create([
    'type' => StepType::NOTIFICATION,
    'configuration' => [
        'notification_class' => \App\Notifications\OrderConfirmation::class,
        'recipients' => ['{{user_id}}'],
    ],
]);
```

### Webhook Step

Make HTTP requests to external services:

```php
$step = $workflow->steps()->create([
    'type' => StepType::WEBHOOK,
    'configuration' => [
        'url' => 'https://api.example.com/webhook',
        'method' => 'POST',
        'headers' => ['Authorization' => 'Bearer token'],
        'payload' => ['data' => '{{context}}'],
    ],
]);
```

[See full documentation for all step types →](docs/step-types.html)

## 🔀 Conditional Branching

Add conditions to steps for dynamic workflow paths:

```php
use AlizHarb\ForgePulse\Enums\StepType;

$step->update([
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            ['field' => 'user.role', 'operator' => '==', 'value' => 'premium'],
            ['field' => 'order.total', 'operator' => '>', 'value' => 100],
        ],
    ],
]);
```

### Supported Operators

- Equality: `==`, `===`, `!=`, `!==`
- Comparison: `>`, `>=`, `<`, `<=`
- Arrays: `in`, `not_in`
- Strings: `contains`, `starts_with`, `ends_with`
- Null checks: `is_null`, `is_not_null`, `is_empty`, `is_not_empty`

## 📋 Workflow Templates

Save workflows as reusable templates:

```php
// Save as template
$template = $workflow->saveAsTemplate('User Onboarding Template');

// Create workflow from template
$newWorkflow = $template->instantiateFromTemplate('New Onboarding');

// Export template to file
$templateManager = app(\AlizHarb\ForgePulse\Services\TemplateManager::class);
$path = $templateManager->export($template);

// Import template from file
$workflow = $templateManager->import($path, 'Imported Workflow');
```

## ⚡ Real-Time Execution Tracking

Monitor workflow execution in real-time:

```blade
<livewire:forgepulse::workflow-execution-tracker :execution="$execution" />
```

The tracker automatically polls for updates and displays:

- Execution status and progress
- Step-by-step execution logs
- Performance metrics
- Error messages

## ⏱️ Timeout Orchestration (v1.1.0)

Configure timeouts for individual steps to prevent long-running operations:

```php
$step->update([
    'timeout' => 30, // seconds
]);
```

If a step exceeds its timeout, it will be automatically terminated and marked as failed. Requires the `pcntl` PHP extension. Gracefully degrades if not available.

## ⏸️ Pause/Resume Workflows (v1.1.0)

Pause and resume workflow executions:

```php
// Pause execution
$execution->pause('Waiting for manual approval');

// Resume execution
$execution->resume();

// Check if paused
if ($execution->isPaused()) {
    echo "Paused: " . $execution->pause_reason;
}
```

## 🔄 Parallel Execution (v1.1.0)

Execute multiple steps concurrently for improved performance:

```php
// Configure steps to run in parallel
$step1->update([
    'execution_mode' => 'parallel',
    'parallel_group' => 'email-notifications',
]);

$step2->update([
    'execution_mode' => 'parallel',
    'parallel_group' => 'email-notifications',
]);

// Both steps will execute concurrently
```

## 📅 Execution Scheduling (v1.1.0)

Schedule workflows for future execution:

```php
$execution = $workflow->execute([
    'scheduled_at' => now()->addHours(2),
    'context' => ['user_id' => 123],
]);
```

## 🌐 REST API (v1.1.0)

ForgePulse provides a full REST API for mobile monitoring and integrations:

```bash
# List workflows
GET /api/forgepulse/workflows

# Get workflow details
GET /api/forgepulse/workflows/{id}

# List executions
GET /api/forgepulse/executions

# Get execution details
GET /api/forgepulse/executions/{id}

# Pause execution
POST /api/forgepulse/executions/{id}/pause

# Resume execution
POST /api/forgepulse/executions/{id}/resume
```

Configure API settings in `config/forgepulse.php`:

```php
'api' => [
    'enabled' => true,
    'middleware' => ['api', 'auth:sanctum'],
],
```

## 🔔 Events

ForgePulse dispatches the following events:

- `WorkflowStarted` - When workflow execution begins
- `WorkflowCompleted` - When workflow completes successfully
- `WorkflowFailed` - When workflow execution fails
- `StepExecuted` - After each step execution

Listen to these events in your `EventServiceProvider`:

```php
use AlizHarb\ForgePulse\Events\WorkflowCompleted;
use App\Listeners\SendWorkflowCompletionNotification;

protected $listen = [
    WorkflowCompleted::class => [
        SendWorkflowCompletionNotification::class,
    ],
];
```

## 🌍 Multi-Language Support

ForgePulse includes built-in translations for:

- 🇬🇧 English
- 🇪🇸 Spanish
- 🇫🇷 French
- 🇩🇪 German
- 🇸🇦 Arabic (with RTL support)

Set your application locale:

```php
app()->setLocale('es'); // Spanish
app()->setLocale('fr'); // French
app()->setLocale('de'); // German
app()->setLocale('ar'); // Arabic
```

## ⚙️ Configuration

The configuration file (`config/forgepulse.php`) allows you to customize:

- Execution settings (timeout, retries, queue)
- Role-based permissions
- Template storage
- Event hooks
- Notification channels
- Caching options
- UI preferences

### Team Integration (Optional)

ForgePulse supports optional team integration. To enable it:

1. Enable teams in `config/forgepulse.php`:

   ```php
   'teams' => [
       'enabled' => true,
       'model' => \App\Models\Team::class,
   ],
   ```

2. Ensure your `teams` table exists before running migrations. If enabled, ForgePulse will add a `team_id` foreign key to the `workflows` table.

### Permissions

By default, ForgePulse enforces Role-Based Access Control (RBAC). To disable all permission checks (e.g., for local testing or demos), update your configuration:

```php
'permissions' => [
    'enabled' => false,
    // ...
],
```

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test:coverage
```

Run static analysis:

```bash
composer analyse
```

Format code:

```bash
composer format
```

## 🔒 Security

If you discover any security-related issues, please email harbzali@gmail.com instead of using the issue tracker.

## 👥 Credits

- [Ali Harb](https://github.com/AlizHarb)
- [All Contributors](../../contributors)

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## 💖 Support

If you find this package helpful, please consider:

- ⭐ Starring the repository
- 🐛 Reporting bugs
- 💡 Suggesting new features
- 📖 Improving documentation
- 🔀 Contributing code

---

**Made with ❤️ by [Ali Harb](https://alizharb.com)**

**Release Date:** November 26, 2025 | **Version:** 1.1.0
