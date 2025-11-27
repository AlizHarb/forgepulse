# Installation

Get started with ForgePulse in minutes using Composer.

## Requirements

- PHP 8.3, 8.4, or 8.5
- Laravel 12.x
- Livewire 4.x

## Step 1: Install via Composer

Install the package using Composer:

```bash
composer require alizharb/forgepulse
```

## Step 2: Publish Configuration

Publish the configuration file to customize ForgePulse:

```bash
php artisan vendor:publish --tag=forgepulse-config
```

This creates `config/forgepulse.php` where you can configure execution settings, permissions, and more.

## Step 3: Run Migrations

Publish and run the database migrations:

```bash
php artisan vendor:publish --tag=forgepulse-migrations
php artisan migrate
```

This creates 4 tables:

- `workflows` - Stores workflow definitions
- `workflow_steps` - Stores individual workflow steps
- `workflow_executions` - Tracks workflow execution instances
- `workflow_execution_logs` - Logs for each step execution

## Step 4: Publish Assets (Optional)

Optionally publish views, assets, and language files:

```bash
# Publish Blade views
php artisan vendor:publish --tag=forgepulse-views

# Publish CSS/JS assets
php artisan vendor:publish --tag=forgepulse-assets

# Publish language files
php artisan vendor:publish --tag=forgepulse-lang
```

## Verification

To verify the installation, you can check if the tables were created:

```bash
php artisan tinker
```

```php
use AlizHarb\ForgePulse\Models\Workflow;
Workflow::count(); // Should return 0
```

✅ **All set!** You're ready to create your first workflow. Continue to the [Quick Start](#quick-start) section.
