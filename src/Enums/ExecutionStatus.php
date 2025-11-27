<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Enums;

/**
 * Execution Status Enum
 *
 * Represents the various states a workflow execution can be in.
 */
enum ExecutionStatus: string
{
    case PENDING = 'pending';
    case RUNNING = 'running';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';

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
            self::CANCELLED => 'Cancelled',
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
            self::CANCELLED => 'yellow',
        };
    }

    /**
     * Check if the execution is in progress.
     */
    public function isInProgress(): bool
    {
        return match ($this) {
            self::PENDING, self::RUNNING => true,
            default => false,
        };
    }

    /**
     * Check if the execution is finished.
     */
    public function isFinished(): bool
    {
        return match ($this) {
            self::COMPLETED, self::FAILED, self::CANCELLED => true,
            default => false,
        };
    }

    /**
     * Get all in-progress statuses.
     *
     * @return array<self>
     */
    public static function inProgress(): array
    {
        return [self::PENDING, self::RUNNING];
    }

    /**
     * Get all finished statuses.
     *
     * @return array<self>
     */
    public static function finished(): array
    {
        return [self::COMPLETED, self::FAILED, self::CANCELLED];
    }
}
