<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Services\StepHandlers;

use AlizHarb\FlowForge\Models\WorkflowStep;
use Illuminate\Support\Facades\Notification;

/**
 * Notification Handler
 *
 * Sends notifications during workflow execution.
 */
class NotificationHandler
{
    /**
     * Handle the step execution.
     *
     * @param  WorkflowStep  $step  The step to execute
     * @param  array<string, mixed>  $context  Execution context
     * @return array<string, mixed> Output data
     */
    public function handle(WorkflowStep $step, array $context): array
    {
        /** @var array<string, mixed> $config */
        $config = $step->configuration;
        $notificationClass = $config['notification_class'] ?? null;
        $recipients = $config['recipients'] ?? [];

        if (! $notificationClass || ! class_exists($notificationClass)) {
            throw new \RuntimeException("Notification class not found: {$notificationClass}");
        }

        $notification = new $notificationClass($context);

        // Resolve recipients
        $users = $this->resolveRecipients($recipients, $context);

        Notification::send($users, $notification);

        return [
            'notification_sent' => true,
            'recipients_count' => count($users),
        ];
    }

    /**
     * Resolve notification recipients.
     *
     * @param  array<int, string|int>  $recipients  Recipient configuration
     * @param  array<string, mixed>  $context  Execution context
     * @return array<int, \Illuminate\Database\Eloquent\Model> User models
     */
    protected function resolveRecipients(array $recipients, array $context): array
    {
        $users = [];
        $userModel = config('auth.providers.users.model');

        foreach ($recipients as $recipient) {
            if (is_numeric($recipient)) {
                $users[] = $userModel::find($recipient);
            } elseif (str_starts_with($recipient, '{{')) {
                // Context variable
                $key = trim($recipient, '{}');
                $userId = data_get($context, $key);
                if ($userId) {
                    $users[] = $userModel::find($userId);
                }
            }
        }

        return array_filter($users);
    }
}
