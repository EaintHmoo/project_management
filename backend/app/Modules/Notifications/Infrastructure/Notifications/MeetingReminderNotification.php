<?php

namespace App\Modules\Notifications\Infrastructure\Notifications;

use App\Modules\Meetings\Domain\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Meeting $meeting,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Meeting starts in 15 minutes',
            'body' => sprintf('"%s" starts at %s', $this->meeting->title, $this->meeting->starts_at->format('g:i A T')),
            'action_url' => "/meetings/{$this->meeting->id}",
            'meeting_id' => $this->meeting->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(sprintf('"%s" starts in 15 minutes', $this->meeting->title))
            ->line(sprintf('Your meeting "%s" starts at %s.', $this->meeting->title, $this->meeting->starts_at->format('g:i A T')))
            ->action('View meeting', url("/meetings/{$this->meeting->id}"));
    }
}
