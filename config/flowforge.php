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
        'timeout' => env('FLOWFORGE_TIMEOUT', 300),

        // Maximum retry attempts for failed steps
        'max_retries' => env('FLOWFORGE_MAX_RETRIES', 3),

        // Delay between retries (in seconds)
        'retry_delay' => env('FLOWFORGE_RETRY_DELAY', 5),

        // Queue connection for workflow jobs
        'queue_connection' => env('FLOWFORGE_QUEUE_CONNECTION', 'default'),

        // Queue name for workflow jobs
        'queue_name' => env('FLOWFORGE_QUEUE_NAME', 'workflows'),

        // Enable async execution by default
        'async_by_default' => env('FLOWFORGE_ASYNC', true),
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
        'enabled' => env('FLOWFORGE_TEAMS_ENABLED', false),

        // Team model class
        'model' => env('FLOWFORGE_TEAM_MODEL', 'App\\Models\\Team'),
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
        'enabled' => env('FLOWFORGE_RBAC_ENABLED', true),

        // Roles that can create workflows
        'can_create' => ['admin', 'workflow-manager'],

        // Roles that can execute workflows
        'can_execute' => ['admin', 'workflow-manager', 'workflow-executor'],

        // Roles that can manage templates
        'can_manage_templates' => ['admin', 'workflow-manager'],

        // Use team-based permissions (requires Laravel Teams or similar)
        'team_based' => env('FLOWFORGE_TEAM_BASED', false),
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
        'disk' => env('FLOWFORGE_TEMPLATE_DISK', 'local'),

        // Directory for template files
        'directory' => 'workflow-templates',

        // Enable template versioning
        'versioning' => env('FLOWFORGE_TEMPLATE_VERSIONING', true),

        // Enable template sharing between users/teams
        'sharing' => env('FLOWFORGE_TEMPLATE_SHARING', true),
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
        'enabled' => env('FLOWFORGE_NOTIFICATIONS_ENABLED', true),

        // Notification channels
        'channels' => ['mail', 'database'],

        // Notify on workflow completion
        'on_completion' => env('FLOWFORGE_NOTIFY_COMPLETION', true),

        // Notify on workflow failure
        'on_failure' => env('FLOWFORGE_NOTIFY_FAILURE', true),
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
        'enabled' => env('FLOWFORGE_CACHE_ENABLED', true),

        // Cache TTL (in seconds)
        'ttl' => env('FLOWFORGE_CACHE_TTL', 3600),

        // Cache key prefix
        'prefix' => 'flowforge',

        // Cache store
        'store' => env('FLOWFORGE_CACHE_STORE', 'default'),
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
        'action' => \AlizHarb\FlowForge\Services\StepHandlers\ActionHandler::class,
        'condition' => \AlizHarb\FlowForge\Services\StepHandlers\ConditionHandler::class,
        'delay' => \AlizHarb\FlowForge\Services\StepHandlers\DelayHandler::class,
        'notification' => \AlizHarb\FlowForge\Services\StepHandlers\NotificationHandler::class,
        'webhook' => \AlizHarb\FlowForge\Services\StepHandlers\WebhookHandler::class,
        'event' => \AlizHarb\FlowForge\Services\StepHandlers\EventHandler::class,
        'job' => \AlizHarb\FlowForge\Services\StepHandlers\JobHandler::class,
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
        'enabled' => env('FLOWFORGE_LOGGING_ENABLED', true),

        // Log channel
        'channel' => env('FLOWFORGE_LOG_CHANNEL', 'stack'),

        // Log input/output data
        'log_data' => env('FLOWFORGE_LOG_DATA', true),

        // Maximum log retention (in days)
        'retention_days' => env('FLOWFORGE_LOG_RETENTION', 30),
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
        'dark_mode' => env('FLOWFORGE_DARK_MODE', true),

        // Auto-save interval (in seconds)
        'autosave_interval' => env('FLOWFORGE_AUTOSAVE_INTERVAL', 30),

        // Enable grid snapping in canvas
        'grid_snap' => env('FLOWFORGE_GRID_SNAP', true),

        // Grid size (in pixels)
        'grid_size' => env('FLOWFORGE_GRID_SIZE', 20),

        // Enable zoom controls
        'zoom_enabled' => env('FLOWFORGE_ZOOM_ENABLED', true),
    ],
];
