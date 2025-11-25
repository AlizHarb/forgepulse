<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Enums;

/**
 * Log Status Enum
 *
 * Represents the status of a workflow step execution log.
 */
enum LogStatus: string
{
    case PENDING = 'pending';
    case RUNNING = 'running';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case SKIPPED = 'skipped';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::RUNNING => 'Running',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::SKIPPED => 'Skipped',
        };
    }

    /**
     * Get the color for the status.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::RUNNING => 'blue',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
            self::SKIPPED => 'yellow',
        };
    }

    /**
     * Get the icon for the status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            self::RUNNING => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
            self::COMPLETED => 'M5 13l4 4L19 7',
            self::FAILED => 'M6 18L18 6M6 6l12 12',
            self::SKIPPED => 'M13 5l7 7-7 7M5 5l7 7-7 7',
        };
    }
}
