<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Workflow Execution Settings
    |--------------------------------------------------------------------------
    |
    | Configure how workflows are executed, including timeout limits,
    | retry attempts, and queue settings.
    |
    */

    'execution' => [
        // Maximum execution time for a workflow (in seconds)
        'timeout' => env('FORGEPULSE_TIMEOUT', 300),

        // Maximum retry attempts for failed steps
        'max_retries' => env('FORGEPULSE_MAX_RETRIES', 3),

        // Delay between retries (in seconds)
        'retry_delay' => env('FORGEPULSE_RETRY_DELAY', 5),

        // Queue connection for workflow jobs
        'queue_connection' => env('FORGEPULSE_QUEUE_CONNECTION', 'default'),

        // Queue name for workflow jobs
        'queue_name' => env('FORGEPULSE_QUEUE_NAME', 'workflows'),

        // Enable async execution by default
        'async_by_default' => env('FORGEPULSE_ASYNC', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Team Integration
    |--------------------------------------------------------------------------
    |
    | Configure team integration settings.
    |
    */

    'teams' => [
        // Enable team support (adds team_id column and relationships)
        'enabled' => env('FORGEPULSE_TEAMS_ENABLED', false),

        // Team model class
        'model' => env('FORGEPULSE_TEAM_MODEL', 'App\\Models\\Team'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Role-Based Access Control
    |--------------------------------------------------------------------------
    |
    | Configure permissions and roles for workflow management.
    |
    */

    'permissions' => [
        // Enable role-based access control
        // Set to false to disable all permission checks (useful for testing/demo)
        'enabled' => env('FORGEPULSE_RBAC_ENABLED', true),

        // Roles that can create workflows
        'can_create' => ['admin', 'workflow-manager'],

        // Roles that can execute workflows
        'can_execute' => ['admin', 'workflow-manager', 'workflow-executor'],

        // Roles that can manage templates
        'can_manage_templates' => ['admin', 'workflow-manager'],

        // Use team-based permissions (requires Laravel Teams or similar)
        'team_based' => env('FORGEPULSE_TEAM_BASED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Storage
    |--------------------------------------------------------------------------
    |
    | Configure how workflow templates are stored and managed.
    |
    */

    'templates' => [
        // Storage disk for template exports
        'disk' => env('FORGEPULSE_TEMPLATE_DISK', 'local'),

        // Directory for template files
        'directory' => 'workflow-templates',

        // Enable template versioning
        'versioning' => env('FORGEPULSE_TEMPLATE_VERSIONING', true),

        // Enable template sharing between users/teams
        'sharing' => env('FORGEPULSE_TEMPLATE_SHARING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Workflow Versioning (v1.2.0)
    |--------------------------------------------------------------------------
    |
    | Configure automatic workflow versioning and rollback functionality.
    |
    */

    'versioning' => [
        // Enable automatic workflow versioning
        'enabled' => env('FORGEPULSE_VERSIONING_ENABLED', true),

        // Maximum number of versions to keep per workflow (0 = unlimited)
        'max_versions' => env('FORGEPULSE_MAX_VERSIONS', 50),

        // Automatically create version on save
        'auto_version_on_save' => env('FORGEPULSE_AUTO_VERSION', true),

        // Version retention days (older versions will be pruned)
        'retention_days' => env('FORGEPULSE_VERSION_RETENTION', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Hooks
    |--------------------------------------------------------------------------
    |
    | Configure which events should be dispatched during workflow execution.
    |
    */

    'events' => [
        // Dispatch WorkflowStarted event
        'workflow_started' => true,

        // Dispatch WorkflowCompleted event
        'workflow_completed' => true,

        // Dispatch WorkflowFailed event
        'workflow_failed' => true,

        // Dispatch StepExecuted event
        'step_executed' => true,

        // Dispatch StepFailed event
        'step_failed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure workflow notifications.
    |
    */

    'notifications' => [
        // Enable notifications
        'enabled' => env('FORGEPULSE_NOTIFICATIONS_ENABLED', true),

        // Notification channels
        'channels' => ['mail', 'database'],

        // Notify on workflow completion
        'on_completion' => env('FORGEPULSE_NOTIFY_COMPLETION', true),

        // Notify on workflow failure
        'on_failure' => env('FORGEPULSE_NOTIFY_FAILURE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance & Caching
    |--------------------------------------------------------------------------
    |
    | Configure caching and performance optimization settings.
    |
    */

    'cache' => [
        // Enable workflow definition caching
        'enabled' => env('FORGEPULSE_CACHE_ENABLED', true),

        // Cache TTL (in seconds)
        'ttl' => env('FORGEPULSE_CACHE_TTL', 3600),

        // Cache key prefix
        'prefix' => 'forgepulse',

        // Cache store
        'store' => env('FORGEPULSE_CACHE_STORE', 'default'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Workflow Step Types
    |--------------------------------------------------------------------------
    |
    | Define available step types and their handlers.
    |
    */

    'step_types' => [
        'action' => \AlizHarb\ForgePulse\Services\StepHandlers\ActionHandler::class,
        'condition' => \AlizHarb\ForgePulse\Services\StepHandlers\ConditionHandler::class,
        'delay' => \AlizHarb\ForgePulse\Services\StepHandlers\DelayHandler::class,
        'notification' => \AlizHarb\ForgePulse\Services\StepHandlers\NotificationHandler::class,
        'webhook' => \AlizHarb\ForgePulse\Services\StepHandlers\WebhookHandler::class,
        'event' => \AlizHarb\ForgePulse\Services\StepHandlers\EventHandler::class,
        'job' => \AlizHarb\ForgePulse\Services\StepHandlers\JobHandler::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Configure workflow execution logging.
    |
    */

    'logging' => [
        // Enable detailed execution logging
        'enabled' => env('FORGEPULSE_LOGGING_ENABLED', true),

        // Log channel
        'channel' => env('FORGEPULSE_LOG_CHANNEL', 'stack'),

        // Log input/output data
        'log_data' => env('FORGEPULSE_LOG_DATA', true),

        // Maximum log retention (in days)
        'retention_days' => env('FORGEPULSE_LOG_RETENTION', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    |
    | Configure the workflow builder UI.
    |
    */

    'ui' => [
        // Enable dark mode
        'dark_mode' => env('FORGEPULSE_DARK_MODE', true),

        // Auto-save interval (in seconds)
        'autosave_interval' => env('FORGEPULSE_AUTOSAVE_INTERVAL', 30),

        // Enable grid snapping in canvas
        'grid_snap' => env('FORGEPULSE_GRID_SNAP', true),

        // Grid size (in pixels)
        'grid_size' => env('FORGEPULSE_GRID_SIZE', 20),

        // Enable zoom controls
        'zoom_enabled' => env('FORGEPULSE_ZOOM_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    |
    | Configure the REST API for mobile monitoring and integrations.
    |
    */

    'api' => [
        // Enable REST API endpoints
        'enabled' => env('FORGEPULSE_API_ENABLED', true),

        // API middleware
        'middleware' => ['api', 'auth:sanctum'],

        // API rate limiting
        'rate_limit' => env('FORGEPULSE_API_RATE_LIMIT', '60,1'),
    ],
];
