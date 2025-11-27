<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ForgePulse Language Lines
    |--------------------------------------------------------------------------
    */

    'workflow' => [
        'title' => 'Workflows',
        'create' => 'Create Workflow',
        'edit' => 'Edit Workflow',
        'delete' => 'Delete Workflow',
        'name' => 'Workflow Name',
        'description' => 'Description',
        'status' => 'Status',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'execute' => 'Execute Workflow',
        'save' => 'Save Workflow',
        'cancel' => 'Cancel',
    ],

    'status' => [
        'draft' => 'Draft',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'archived' => 'Archived',
        'pending' => 'Pending',
        'running' => 'Running',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled',
        'skipped' => 'Skipped',
    ],

    'step' => [
        'title' => 'Steps',
        'add' => 'Add Step',
        'edit' => 'Edit Step',
        'delete' => 'Delete Step',
        'name' => 'Step Name',
        'type' => 'Step Type',
        'configuration' => 'Configuration',
        'conditions' => 'Conditions',
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
    ],

    'step_types' => [
        'action' => 'Action',
        'condition' => 'Condition',
        'delay' => 'Delay',
        'notification' => 'Notification',
        'webhook' => 'Webhook',
        'event' => 'Event',
        'job' => 'Job',
    ],

    'execution' => [
        'title' => 'Executions',
        'started_at' => 'Started At',
        'completed_at' => 'Completed At',
        'duration' => 'Duration',
        'error' => 'Error',
        'retry' => 'Retry',
        'logs' => 'Execution Logs',
        'context' => 'Context',
        'output' => 'Output',
    ],

    'template' => [
        'title' => 'Templates',
        'save_as' => 'Save as Template',
        'load' => 'Load Template',
        'export' => 'Export Template',
        'import' => 'Import Template',
        'name' => 'Template Name',
    ],

    'builder' => [
        'title' => 'Workflow Builder',
        'zoom_in' => 'Zoom In',
        'zoom_out' => 'Zoom Out',
        'reset_zoom' => 'Reset Zoom',
        'grid_snap' => 'Grid Snap',
        'save' => 'Save',
        'toolbar' => 'Toolbar',
    ],

    'messages' => [
        'success' => [
            'created' => 'Workflow created successfully',
            'updated' => 'Workflow updated successfully',
            'deleted' => 'Workflow deleted successfully',
            'executed' => 'Workflow executed successfully',
            'saved' => 'Changes saved successfully',
        ],
        'error' => [
            'not_found' => 'Workflow not found',
            'execution_failed' => 'Workflow execution failed',
            'validation_failed' => 'Validation failed',
            'unauthorized' => 'Unauthorized action',
        ],
    ],

    'validation' => [
        'required' => 'The :attribute field is required',
        'string' => 'The :attribute must be a string',
        'max' => 'The :attribute may not be greater than :max characters',
        'array' => 'The :attribute must be an array',
    ],

    // Version History (v1.2.0)
    'version_history' => [
        'title' => 'Version History',
        'no_versions' => 'No versions available yet',
        'versions' => 'Versions',
        'version_details' => 'Version Details',
        'version_number' => 'Version',
        'created' => 'Created',
        'steps_count' => 'Steps',
        'restored_at' => 'Restored At',
        'restored' => 'Restored',
        'steps' => 'steps',
        'by' => 'by',
        'select_version' => 'Select a version to view details',
        'compare' => 'Compare',
        'restore' => 'Restore Version',
        'comparison' => 'Version Comparison',
        'steps_added' => 'Steps Added/Removed',
        'steps_modified' => 'Steps Modified',
        'config_changed' => 'Configuration Changed',
        'confirm_rollback' => 'Confirm Rollback',
        'rollback_warning' => 'Are you sure you want to restore this version? This will replace the current workflow configuration and steps.',
        'note' => 'Note',
        'backup_created' => 'A backup of the current state will be created automatically before restoring.',
        'confirm_restore' => 'Restore Version',
    ],

    'yes' => 'Yes',
    'no' => 'No',
    'cancel' => 'Cancel',
];
