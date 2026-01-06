<?php

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

        $stats = [
            'total_tenants' => Tenant::count(),
            'bills_this_month' => Bill::whereBetween('created_at', [$startOfMonth, $now])->count(),
            'notifications_sent' => Bill::whereNotNull('notified_at')
                ->whereBetween('notified_at', [$startOfMonth, $now])
                ->count(),
            'failed_notifications' => Bill::whereNotNull('notification_error')
                ->whereNull('notified_at')
                ->count(),
        ];

        return view('livewire.dashboard.stats', [
            'stats' => $stats
        ]);
    }
}
