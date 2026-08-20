<?php

namespace App\Modules\Notifications\Infrastructure\Notifications;

use App\Models\User;
use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Task $task,
        private readonly int $assignedById,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $assignedBy = User::find($this->assignedById);

        return [
            'title' => 'You were assigned a task',
            'body' => sprintf(
                '%s assigned you "%s"',
                $assignedBy?->name ?? 'Someone',
                $this->task->title,
            ),
            'action_url' => "/projects/{$this->task->project_id}",
            'task_id' => $this->task->id,
            'project_id' => $this->task->project_id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $assignedBy = User::find($this->assignedById);

        return (new MailMessage)
            ->subject('You were assigned a task')
            ->line(sprintf(
                '%s assigned you "%s".',
                $assignedBy?->name ?? 'Someone',
                $this->task->title,
            ))
            ->action('View project', url("/projects/{$this->task->project_id}"));
    }
}
