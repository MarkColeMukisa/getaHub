<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::job(new \App\Jobs\AutomateSmsNotificationsJob)->everyFifteenSeconds()->name('automate_sms_notifications');
\Illuminate\Support\Facades\Schedule::job(new \App\Jobs\VerifyNotificationsJob)->everyThirtySeconds()->name('verify_sms_notifications');
