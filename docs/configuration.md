# Configuration

Customize ForgePulse to fit your application's needs.

## Configuration File

After publishing the configuration file, you'll find it at `config/forgepulse.php`:

```php
return [
    // Execution Settings
    'execution' => [
        'default_async' => true,
        'queue' => 'default',
        'timeout' => 300, // 5 minutes
        'retry_attempts' => 3,
        'retry_delay' => 60, // seconds
    ],

    // Permissions
    'permissions' => [
        'enabled' => true,
        'super_admin_role' => 'super-admin',
    ],

    // Localization
    'locale' => [
        'default' => 'en',
        'fallback' => 'en',
        'supported' => ['en', 'ar', 'fr', 'es', 'de'],
    ],

    // UI Settings
    'ui' => [
        'theme' => 'light', // light, dark, auto
        'items_per_page' => 15,
    ],
];
```

## Environment Variables

You can override configuration values using environment variables:

```env
FORGEPULSE_DEFAULT_ASYNC=true
FORGEPULSE_QUEUE=workflows
FORGEPULSE_TIMEOUT=600
FORGEPULSE_PERMISSIONS_ENABLED=true
```

## Queue Configuration

Configure which queue ForgePulse should use:

```php
// config/forgepulse.php
'execution' => [
    'queue' => env('FORGEPULSE_QUEUE', 'workflows'),
],
```

Make sure your queue worker is running:

```bash
php artisan queue:work --queue=workflows
```

## Timeout Settings

Set global and per-step timeouts:

```php
// Global timeout (in config)
'execution' => [
    'timeout' => 300, // 5 minutes
],

// Per-step timeout
$step->update(['timeout' => 60]); // 1 minute
```

## Permissions

Enable or disable the permission system:

```php
'permissions' => [
    'enabled' => true,
    'super_admin_role' => 'super-admin',
],
```

When enabled, users need appropriate permissions to create, edit, or execute workflows.
