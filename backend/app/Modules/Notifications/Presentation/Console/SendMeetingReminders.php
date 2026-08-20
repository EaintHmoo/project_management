<?php

namespace App\Modules\Notifications\Presentation\Console;

use App\Modules\Meetings\Domain\Enums\MeetingStatus;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Notifications\Infrastructure\Notifications\MeetingReminderNotification;
use Illuminate\Console\Command;

class SendMeetingReminders extends Command
{
    protected $signature = 'notifications:send-meeting-reminders';

    protected $description = 'Notify hosts and participants of meetings starting within the next 15 minutes.';

    public function handle(): int
    {
        $meetings = Meeting::query()
            ->where('status', MeetingStatus::Scheduled)
            ->whereNull('reminder_sent_at')
            ->whereBetween('starts_at', [now(), now()->addMinutes(15)])
            ->with(['host', 'participants'])
            ->get();

        foreach ($meetings as $meeting) {
            $recipients = $meeting->participants->push($meeting->host)->unique('id');

            foreach ($recipients as $recipient) {
                $recipient->notify(new MeetingReminderNotification($meeting));
            }

            $meeting->update(['reminder_sent_at' => now()]);
        }

        $this->info("Sent reminders for {$meetings->count()} meeting(s).");

        return self::SUCCESS;
    }
}
