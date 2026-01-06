<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class VerifyNotificationsJob implements ShouldQueue
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
        $report = $service->getSummaryReport();

        \Illuminate\Support\Facades\Mail::to('joegapp256@gmail.com')
            ->send(new \App\Mail\NotificationSummaryMail($report));

        \Illuminate\Support\Facades\Log::info("VerifyNotificationsJob completed. Summary report emailed to admin.");
    }
}
