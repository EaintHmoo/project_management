<?php

namespace App\Modules\Auth\Infrastructure\TwoFactor\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $code,
        private readonly int $expiresInMinutes,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your verification code')
            ->line("Your verification code is: {$this->code}")
            ->line("This code expires in {$this->expiresInMinutes} minutes.")
            ->line('If you did not attempt to sign in, you can safely ignore this email.');
    }
}
