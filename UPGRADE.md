# Upgrade Guide

## Upgrading from 1.1.0 to 1.2.0

ForgePulse 1.2.0 introduces workflow versioning, advanced conditional operators, and enhanced visual designer features. This guide will help you upgrade smoothly.

### Requirements

- PHP 8.3, 8.4, or 8.5
- Laravel 12
- Livewire 4

### Step 1: Update Package

Update your `composer.json`:

```bash
composer update alizharb/forgepulse
```

### Step 2: Run Migrations

One new migration has been added:

```bash
php artisan migrate
```

This will create the `workflow_versions` table for version tracking.

### Step 3: Publish Updated Assets (Optional)

If you want the enhanced dark mode CSS:

```bash
php artisan vendor:publish --tag=forgepulse-assets --force
```

### Step 4: Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## New Features in v1.2.0

### 1. Workflow Versioning

Automatic version tracking with rollback capability:

```php
// Automatic versioning (enabled by default)
$workflow->save(); // Creates version automatically

// Manual version creation
$version = $workflow->createVersion('Before major changes');

// View version history
$versions = $workflow->versions;

// Rollback to previous version
$workflow->restoreVersion($versionId);

// Compare versions
$diff = $latestVersion->compare($previousVersion);
```

**Configuration**:

```php
// config/forgepulse.php
'versioning' => [
    'enabled' => true,
    'max_versions' => 50,
    'auto_version_on_save' => true,
    'retention_days' => 90,
],
```

### 2. Advanced Conditional Operators

10 new operators for more powerful workflow logic:

```php
$step->update([
    'conditions' => [
        'operator' => 'and',
        'rules' => [
            // Regex pattern matching
            ['field' => 'email', 'operator' => 'regex', 'value' => '/^[a-z]+@company\.com$/'],

            // Range checks
            ['field' => 'age', 'operator' => 'between', 'value' => [18, 65]],

            // Array operations
            ['field' => 'permissions', 'operator' => 'contains_all', 'value' => ['read', 'write']],

            // Length comparisons
            ['field' => 'description', 'operator' => 'length_gt', 'value' => 100],
        ],
    ],
]);
```

**New Operators**:

- `regex`, `not_regex` - Pattern matching
- `between`, `not_between` - Range checks
- `in_array`, `not_in_array` - Array membership
- `contains_all`, `contains_any` - Array subset checks
- `length_eq`, `length_gt`, `length_lt` - Length comparisons

### 3. Enhanced Dark Mode

Improved dark mode with manual toggle support:

```html
<!-- Enable dark mode manually -->
<div data-theme="dark">
  <livewire:forgepulse::workflow-builder :workflow="$workflow" />
</div>
```

---

## Breaking Changes

**None**. This is a backward-compatible release.

---

## Configuration Changes

### New Config Keys

```php
'versioning' => [
    'enabled' => true,
    'max_versions' => 50,
    'auto_version_on_save' => true,
    'retention_days' => 90,
],
```

---

## Database Changes

### New Tables

**workflow_versions**:

- `id` (bigint, primary key)
- `workflow_id` (bigint, foreign key)
- `version_number` (integer)
- `name` (string)
- `description` (text, nullable)
- `configuration` (json, nullable)
- `steps_snapshot` (json)
- `created_by` (bigint, nullable, foreign key)
- `created_at` (timestamp)
- `restored_at` (timestamp, nullable)

---

## API Changes

### New Methods

**Workflow**:

- `versions(): HasMany` - Get workflow versions
- `createVersion(?string $description): WorkflowVersion` - Create version snapshot
- `restoreVersion(int $versionId): bool` - Restore to version
- `latestVersion(): ?WorkflowVersion` - Get latest version

**WorkflowVersion** (New Model):

- `restore(): bool` - Restore workflow to this version
- `compare(WorkflowVersion $other): array` - Compare with another version

**ConditionalEvaluator**:

- Extended with 10 new operators (see above)

---

## Testing Your Upgrade

After upgrading, run your test suite:

```bash
composer test
```

Verify new features:

1. Create a workflow and save it multiple times
2. Check version history: `$workflow->versions`
3. Test rollback functionality
4. Test new conditional operators
5. Verify dark mode works correctly

---

## Rollback

If you need to rollback:

```bash
composer require alizharb/forgepulse:^1.1
php artisan migrate:rollback --step=1
```

---

## Upgrading from 1.0.0 to 1.1.0

ForgePulse 1.1.0 introduces several new features and enhancements. This guide will help you upgrade smoothly.

### Requirements

- PHP 8.3, 8.4, or 8.5
- Laravel 12
- Livewire 4

### Step 1: Update Package

Update your `composer.json`:

```bash
composer update alizharb/forgepulse
```

### Step 2: Run Migrations

Four new migrations have been added:

```bash
php artisan migrate
```

This will add:

- `timeout` column to `workflow_steps`
- `paused_at` and `pause_reason` to `workflow_executions`
- `execution_mode` and `parallel_group` to `workflow_steps`
- `scheduled_at` and `schedule_config` to `workflow_executions`

### Step 3: Publish Updated Config (Optional)

If you want the new API configuration:

```bash
php artisan vendor:publish --tag=forgepulse-config --force
```

**Note**: This will overwrite your existing config. Back it up first!

### Step 4: Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## New Features

### 1. Timeout Orchestration

Steps can now have a timeout. If a step exceeds the timeout, it will be terminated.

```php
$step->update([
    'timeout' => 30, // seconds
]);
```

**Requirements**: The `pcntl` PHP extension for timeout enforcement. Gracefully degrades if not available.

### 2. Pause/Resume Workflows

Pause and resume workflow executions:

```php
// Pause
$execution->pause('User requested pause');

// Resume
$execution->resume();

// Check if paused
if ($execution->isPaused()) {
    // ...
}
```

### 3. Parallel Execution Paths

Execute multiple steps concurrently:

```php
$step->update([
    'execution_mode' => 'parallel',
    'parallel_group' => 'group-1',
]);
```

All steps with the same `parallel_group` will execute concurrently.

### 4. Execution Scheduling

Schedule workflows for future execution:

```php
$execution->update([
    'scheduled_at' => now()->addHours(2),
]);
```

### 5. REST API for Monitoring

New API endpoints for mobile monitoring and integrations:

```
GET  /api/forgepulse/workflows
GET  /api/forgepulse/workflows/{id}
GET  /api/forgepulse/executions
GET  /api/forgepulse/executions/{id}
POST /api/forgepulse/executions/{id}/pause
POST /api/forgepulse/executions/{id}/resume
```

**Configuration**:

```php
// config/forgepulse.php
'api' => [
    'enabled' => true,
    'middleware' => ['api', 'auth:sanctum'],
],
```

### 6. Dark Mode Support

Dark mode is now enabled by default in the UI:

```php
// config/forgepulse.php
'ui' => [
    'dark_mode' => true,
],
```

---

## Breaking Changes

**None**. This is a backward-compatible release.

---

## Configuration Changes

### New Config Keys

```php
'api' => [
    'enabled' => true,
    'middleware' => ['api', 'auth:sanctum'],
    'rate_limit' => '60,1',
],
```

### Updated Config Keys

No existing keys were changed.

---

## Database Changes

### New Columns

**workflow_steps**:

- `timeout` (integer, nullable)
- `execution_mode` (string, default: 'sequential')
- `parallel_group` (string, nullable)

**workflow_executions**:

- `paused_at` (timestamp, nullable)
- `pause_reason` (text, nullable)
- `scheduled_at` (timestamp, nullable)
- `schedule_config` (json, nullable)

---

## API Changes

### New Methods

**WorkflowExecution**:

- `pause(?string $reason = null): void`
- `resume(): void`
- `isPaused(): bool`

**WorkflowStep**:

- `getTimeout(): ?int`

---

## Testing Your Upgrade

After upgrading, run your test suite:

```bash
composer test
```

Verify all features:

1. Create a workflow with timeout
2. Test pause/resume functionality
3. Test API endpoints (if enabled)
4. Check dark mode in UI

---

## Rollback

If you need to rollback:

```bash
composer require alizharb/forgepulse:^1.0
php artisan migrate:rollback --step=4
```

---

## Support

If you encounter issues:

1. Check the [CHANGELOG](CHANGELOG.md)
2. Review the [documentation](docs/index.html)
3. Open an issue on [GitHub](https://github.com/alizharb/forgepulse/issues)

---

**Last Updated**: 2025-11-27
