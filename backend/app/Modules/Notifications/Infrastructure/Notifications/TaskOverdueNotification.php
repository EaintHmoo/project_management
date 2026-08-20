<?php

namespace App\Modules\Notifications\Infrastructure\Notifications;

use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Task $task,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Task overdue',
            'body' => sprintf('"%s" was due %s', $this->task->title, $this->task->due_at->format('M j, Y')),
            'action_url' => "/projects/{$this->task->project_id}",
            'task_id' => $this->task->id,
            'project_id' => $this->task->project_id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(sprintf('Task overdue: %s', $this->task->title))
            ->line(sprintf('"%s" was due %s and is still not done.', $this->task->title, $this->task->due_at->format('M j, Y')))
            ->action('View project', url("/projects/{$this->task->project_id}"));
    }
}
