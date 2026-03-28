<?php

use App\Services\SettingsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes / Scheduled Tasks
|--------------------------------------------------------------------------
|
| LoggyShip uses a configurable publish frequency to determine how often
| GitHub activity is synced and drafts are generated. The schedule adapts
| based on the 'publish_frequency' setting.
|
*/

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync repositories every hour regardless of publish frequency
Schedule::command('loggyship:sync')->hourly();

// Generate drafts based on configured publish frequency
Schedule::command('loggyship:generate')->when(function () {
    $settings = app(SettingsService::class);
    $frequency = $settings->get('general', 'publish_frequency', 'realtime');

    if ($frequency === 'realtime') {
        return true; // runs every time the scheduler fires (every minute)
    }

    $lastRun = $settings->get('general', 'last_generate_run');
    if (! $lastRun) {
        return true; // never run before, go ahead
    }

    $lastRunAt = \Carbon\Carbon::parse($lastRun);
    $now = now();

    return match ($frequency) {
        'daily' => $lastRunAt->diffInHours($now) >= 24,
        'every_3_days' => $lastRunAt->diffInHours($now) >= 72,
        'weekly' => $lastRunAt->diffInDays($now) >= 7,
        'biweekly' => $lastRunAt->diffInDays($now) >= 14,
        'monthly' => $lastRunAt->diffInDays($now) >= 30,
        default => true,
    };
})->everyMinute();
