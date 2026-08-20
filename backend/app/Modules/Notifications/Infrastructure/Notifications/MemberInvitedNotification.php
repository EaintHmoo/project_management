<?php

namespace App\Modules\Notifications\Infrastructure\Notifications;

use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberInvitedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly OrganizationMember $membership,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $organization = $this->membership->organization;

        return [
            'title' => sprintf('You were invited to %s', $organization->name),
            'body' => sprintf('Invited as %s', $this->membership->role->value),
            'action_url' => '/invitations',
            'organization_id' => $organization->id,
            'membership_id' => $this->membership->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $organization = $this->membership->organization;

        return (new MailMessage)
            ->subject(sprintf('You were invited to %s', $organization->name))
            ->line(sprintf('You have been invited to join "%s" as %s.', $organization->name, $this->membership->role->value))
            ->action('View invitation', url('/invitations'));
    }
}
