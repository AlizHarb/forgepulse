<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Enums;

/**
 * Workflow Status Enum
 *
 * Represents the various states a workflow can be in.
 */
enum WorkflowStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ARCHIVED = 'archived';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::ARCHIVED => 'Archived',
        };
    }

    /**
     * Get the color for the status.
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::ACTIVE => 'green',
            self::INACTIVE => 'yellow',
            self::ARCHIVED => 'red',
        };
    }

    /**
     * Check if the workflow can be executed.
     */
    public function canExecute(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Get all executable statuses.
     *
     * @return array<self>
     */
    public static function executable(): array
    {
        return [self::ACTIVE];
    }
}
