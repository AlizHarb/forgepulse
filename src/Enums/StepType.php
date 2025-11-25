<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Enums;

/**
 * Step Type Enum
 *
 * Represents the different types of workflow steps available.
 */
enum StepType: string
{
    case ACTION = 'action';
    case CONDITION = 'condition';
    case DELAY = 'delay';
    case NOTIFICATION = 'notification';
    case WEBHOOK = 'webhook';
    case EVENT = 'event';
    case JOB = 'job';

    /**
     * Get the label for the step type.
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTION => 'Action',
            self::CONDITION => 'Condition',
            self::DELAY => 'Delay',
            self::NOTIFICATION => 'Notification',
            self::WEBHOOK => 'Webhook',
            self::EVENT => 'Event',
            self::JOB => 'Job',
        };
    }

    /**
     * Get the icon path for the step type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::ACTION => 'M13 10V3L4 14h7v7l9-11h-7z',
            self::CONDITION => 'M8 16l-4-4m0 0l4-4m-4 4h16',
            self::DELAY => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            self::NOTIFICATION => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            self::WEBHOOK => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
            self::EVENT => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            self::JOB => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        };
    }

    /**
     * Get the handler class for this step type.
     */
    public function handlerClass(): string
    {
        return match ($this) {
            self::ACTION => \AlizHarb\FlowForge\Services\StepHandlers\ActionHandler::class,
            self::CONDITION => \AlizHarb\FlowForge\Services\StepHandlers\ConditionHandler::class,
            self::DELAY => \AlizHarb\FlowForge\Services\StepHandlers\DelayHandler::class,
            self::NOTIFICATION => \AlizHarb\FlowForge\Services\StepHandlers\NotificationHandler::class,
            self::WEBHOOK => \AlizHarb\FlowForge\Services\StepHandlers\WebhookHandler::class,
            self::EVENT => \AlizHarb\FlowForge\Services\StepHandlers\EventHandler::class,
            self::JOB => \AlizHarb\FlowForge\Services\StepHandlers\JobHandler::class,
        };
    }

    /**
     * Get the default configuration for this step type.
     *
     * @return array<string, mixed>
     */
    public function defaultConfiguration(): array
    {
        return match ($this) {
            self::ACTION => ['action_class' => '', 'parameters' => []],
            self::CONDITION => ['conditions' => []],
            self::DELAY => ['seconds' => 5],
            self::NOTIFICATION => ['notification_class' => '', 'recipients' => []],
            self::WEBHOOK => ['url' => '', 'method' => 'POST', 'headers' => [], 'payload' => []],
            self::EVENT => ['event_class' => '', 'parameters' => []],
            self::JOB => ['job_class' => '', 'parameters' => [], 'queue' => null, 'delay' => null],
        };
    }

    /**
     * Get the color for the step type.
     */
    public function color(): string
    {
        return match ($this) {
            self::ACTION => 'blue',
            self::CONDITION => 'yellow',
            self::DELAY => 'pink',
            self::NOTIFICATION => 'green',
            self::WEBHOOK => 'indigo',
            self::EVENT => 'yellow',
            self::JOB => 'blue',
        };
    }
}
