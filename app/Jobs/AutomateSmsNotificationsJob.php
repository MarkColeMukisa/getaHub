<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AutomateSmsNotificationsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\NotificationService $service): void
    {
        $stats = $service->processPendingNotifications();

        if ($stats['total'] > 0) {
            \Illuminate\Support\Facades\Log::info("AutomateSmsNotificationsJob completed: {$stats['success']} succeeded, {$stats['failed']} failed.");
        }
    }
}
