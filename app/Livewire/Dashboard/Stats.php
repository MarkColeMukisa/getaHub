<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Bill;
use App\Models\Tenant;
use Livewire\Component;

class Stats extends Component
{
    // Livewire will auto-refresh this component every 30 seconds
    protected $listeners = ['celebrate' => '$refresh'];

    public function render()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();

        $billCounts = Bill::query()
            ->selectRaw(
                'SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as bills_this_month,'.
                'SUM(CASE WHEN notified_at IS NOT NULL AND notified_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as notifications_sent,'.
                'SUM(CASE WHEN notification_error IS NOT NULL AND notified_at IS NULL THEN 1 ELSE 0 END) as failed_notifications',
                [$startOfMonth, $now, $startOfMonth, $now]
            )
            ->first();

        $stats = [
            'total_tenants' => Tenant::count(),
            'bills_this_month' => (int) $billCounts->bills_this_month,
            'notifications_sent' => (int) $billCounts->notifications_sent,
            'failed_notifications' => (int) $billCounts->failed_notifications,
        ];

        return view('livewire.dashboard.stats', [
            'stats' => $stats,
        ]);
    }
}
