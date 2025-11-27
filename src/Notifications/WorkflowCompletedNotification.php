<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Notifications;

use AlizHarb\ForgePulse\Models\WorkflowExecution;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Workflow Completed Notification
 *
 * Sent when a workflow execution completes successfully.
 */
class WorkflowCompletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public WorkflowExecution $execution
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $_notifiable): array
    {
        return config('forgepulse.notifications.channels', ['mail', 'database']);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $_notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Workflow Completed: '.$this->execution->workflow->name)
            ->line('Your workflow has completed successfully.')
            ->line('Workflow: '.$this->execution->workflow->name)
            ->line('Started: '.$this->execution->started_at?->format('Y-m-d H:i:s'))
            ->line('Completed: '.$this->execution->completed_at?->format('Y-m-d H:i:s'))
            ->line('Duration: '.$this->execution->duration.' seconds')
            ->action('View Execution', url('/workflows/'.$this->execution->workflow_id.'/executions/'.$this->execution->id))
            ->line('Thank you for using ForgePulse!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $_notifiable): array
    {
        return [
            'workflow_id' => $this->execution->workflow_id,
            'workflow_name' => $this->execution->workflow->name,
            'execution_id' => $this->execution->id,
            'status' => $this->execution->status,
            'started_at' => $this->execution->started_at,
            'completed_at' => $this->execution->completed_at,
            'duration' => $this->execution->duration,
        ];
    }
}
