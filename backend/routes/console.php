<?php

use App\Modules\Notifications\Presentation\Console\FlagOverdueTasks;
use App\Modules\Notifications\Presentation\Console\SendMeetingReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendMeetingReminders::class)->everyMinute();
Schedule::command(FlagOverdueTasks::class)->everyFiveMinutes();
