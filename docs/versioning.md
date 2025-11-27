# Workflow Versioning (v1.2.0)

ForgePulse v1.2.0 introduces automatic workflow versioning with rollback capability, enabling you to track changes and restore previous states.

## Features

- **Automatic Versioning**: Every save creates a version snapshot
- **Manual Versioning**: Create versions with custom descriptions
- **Version Comparison**: Compare differences between versions
- **One-Click Rollback**: Restore any previous version
- **Version History UI**: Visual timeline of all changes
- **Backup on Restore**: Automatic backup before rollback

## Configuration

Configure versioning in `config/forgepulse.php`:

```php
'versioning' => [
    // Enable automatic workflow versioning
    'enabled' => true,

    // Maximum number of versions to keep per workflow (0 = unlimited)
    'max_versions' => 50,

    // Automatically create version on save
    'auto_version_on_save' => true,

    // Version retention days (older versions will be pruned)
    'retention_days' => 90,
],
```

## Automatic Versioning

When enabled, ForgePulse automatically creates a version every time you save a workflow:

```php
// Save workflow - automatically creates version
$workflow->save();

// Version is created with description: "Auto-save from workflow builder"
```

## Manual Version Creation

Create versions with custom descriptions before making significant changes:

```php
// Create version with description
$version = $workflow->createVersion('Before adding premium features');

echo "Created version {$version->version_number}";
// Output: Created version 5
```

## Viewing Version History

Access all versions of a workflow:

```php
// Get all versions (ordered by version_number DESC)
$versions = $workflow->versions;

foreach ($versions as $version) {
    echo "Version {$version->version_number}: {$version->description}\n";
    echo "  Created: {$version->created_at}\n";
    echo "  Steps: " . count($version->steps_snapshot) . "\n";

    if ($version->restored_at) {
        echo "  Restored: {$version->restored_at}\n";
    }
}
```

## Getting Latest Version

```php
$latest = $workflow->latestVersion();

if ($latest) {
    echo "Latest version: {$latest->version_number}\n";
    echo "Description: {$latest->description}\n";
}
```

## Comparing Versions

Compare two versions to see what changed:

```php
$version1 = $workflow->versions()->where('version_number', 5)->first();
$version2 = $workflow->versions()->where('version_number', 3)->first();

$diff = $version1->compare($version2);

echo "Steps added/removed: {$diff['steps_added']}\n";
echo "Steps modified: {$diff['steps_modified']}\n";
echo "Configuration changed: " . ($diff['configuration_changed'] ? 'Yes' : 'No') . "\n";
echo "Time difference: {$diff['created_at_diff']}\n";
```

## Restoring a Version

Rollback to any previous version:

```php
// Find the version to restore
$targetVersion = $workflow->versions()
    ->where('version_number', 3)
    ->first();

// Restore the version
$workflow->restoreVersion($targetVersion->id);

// A backup of the current state is created automatically
// before restoring
```

**Important**: Restoring a version:

1. Creates a backup version of the current state
2. Deletes all current steps
3. Recreates steps from the version snapshot
4. Updates workflow configuration
5. Marks the restored version with `restored_at` timestamp

## Version History UI

Access version history from the workflow builder:

1. Click the "History" button in the toolbar
2. View all versions in chronological order
3. Select a version to see details
4. Compare versions to see changes
5. Click "Restore Version" to rollback

## Database Schema

The `workflow_versions` table stores:

| Column           | Type      | Description                               |
| ---------------- | --------- | ----------------------------------------- |
| `id`             | bigint    | Primary key                               |
| `workflow_id`    | bigint    | Foreign key to workflows                  |
| `version_number` | integer   | Sequential version number                 |
| `name`           | string    | Workflow name at this version             |
| `description`    | text      | Version description                       |
| `configuration`  | json      | Workflow configuration snapshot           |
| `steps_snapshot` | json      | Complete steps data                       |
| `created_by`     | bigint    | User who created the version              |
| `created_at`     | timestamp | When version was created                  |
| `restored_at`    | timestamp | When version was restored (if applicable) |

## Best Practices

### 1. Descriptive Version Messages

```php
// Good
$workflow->createVersion('Added email verification step');
$workflow->createVersion('Updated webhook URLs for production');

// Not as helpful
$workflow->createVersion('Changes');
$workflow->createVersion('Update');
```

### 2. Version Before Major Changes

```php
// Create version before significant modifications
$workflow->createVersion('Before refactoring approval process');

// Make your changes
$workflow->steps()->create([...]);
$workflow->save();
```

### 3. Regular Cleanup

Set appropriate retention policies:

```php
'versioning' => [
    'max_versions' => 50,      // Keep last 50 versions
    'retention_days' => 90,    // Delete versions older than 90 days
],
```

### 4. Test Restorations

Always test restored workflows before using in production:

```php
// Restore to test environment first
$testWorkflow = $workflow->replicate();
$testWorkflow->restoreVersion($versionId);

// Verify it works as expected
$execution = $testWorkflow->execute(['test' => true], async: false);

if ($execution->status === 'completed') {
    // Safe to restore production workflow
    $workflow->restoreVersion($versionId);
}
```

## API Reference

### Workflow Methods

#### `versions(): HasMany`

Get all versions of the workflow.

```php
$versions = $workflow->versions;
```

#### `createVersion(?string $description): WorkflowVersion`

Create a new version snapshot.

```php
$version = $workflow->createVersion('My description');
```

#### `restoreVersion(int $versionId): bool`

Restore workflow to a specific version.

```php
$success = $workflow->restoreVersion($versionId);
```

#### `latestVersion(): ?WorkflowVersion`

Get the most recent version.

```php
$latest = $workflow->latestVersion();
```

### WorkflowVersion Methods

#### `restore(): bool`

Restore the workflow to this version's state.

```php
$version->restore();
```

#### `compare(WorkflowVersion $other): array`

Compare this version with another.

```php
$diff = $version1->compare($version2);
```

## Examples

See [versioning-example.php](../examples/versioning-example.php) for comprehensive examples including:

- Automatic versioning
- Manual version creation
- Version history viewing
- Version comparison
- Rollback functionality
- Custom descriptions

## Troubleshooting

### Versions Not Being Created

Check configuration:

```php
config('forgepulse.versioning.enabled') // Should be true
config('forgepulse.versioning.auto_version_on_save') // Should be true
```

### Too Many Versions

Adjust retention settings:

```php
'versioning' => [
    'max_versions' => 20,      // Reduce max versions
    'retention_days' => 30,    // Reduce retention period
],
```

### Restore Failed

Check permissions and ensure version exists:

```php
$version = $workflow->versions()->find($versionId);
if (!$version) {
    throw new \Exception('Version not found');
}

// Check user has update permission
Gate::authorize('update', $workflow);
```

---

**Next**: [Advanced Conditional Operators](advanced-conditionals.md)  
**Previous**: [Configuration](configuration.md)
