<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Bill;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AutomateSmsNotificationsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     *
     * Sends SMS notifications for all bills that have not yet been notified.
     */
    public function handle(NotificationService $notificationService): void
    {
        Bill::whereNull('notified_at')
            ->with('tenant')
            ->each(function (Bill $bill) use ($notificationService) {
                $notificationService->notify($bill);
            });
    }
}
