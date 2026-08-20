<?php

namespace App\Modules\Notifications\Infrastructure\Notifications;

use App\Modules\Tasks\Domain\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class UserMentionedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Comment $comment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $task = $this->comment->task;

        return [
            'title' => sprintf('%s mentioned you', $this->comment->author->name),
            'body' => Str::limit($this->comment->body, 140),
            'action_url' => "/projects/{$task->project_id}",
            'task_id' => $task->id,
            'project_id' => $task->project_id,
            'comment_id' => $this->comment->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $task = $this->comment->task;

        return (new MailMessage)
            ->subject(sprintf('%s mentioned you', $this->comment->author->name))
            ->line(sprintf('%s mentioned you on "%s":', $this->comment->author->name, $task->title))
            ->line(Str::limit($this->comment->body, 280))
            ->action('View project', url("/projects/{$task->project_id}"));
    }
}
