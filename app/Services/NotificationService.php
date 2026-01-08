<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use App\Models\Bill;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class NotificationService
{
    public function __construct(
        protected SmsServiceInterface $smsService
    ) {}



    /**
     * Notify a specific tenant about a bill.
     */
    public function notify(Bill $bill): bool
    {
        $tenant = $bill->tenant;
        if (!$tenant || empty($tenant->contact)) {
            $bill->update([
                'notification_error' => 'Missing contact information',
                'notification_attempts' => $bill->notification_attempts + 1,
            ]);
            return false;
        }

        $message = $this->prepareMessage($bill);
        $contact = trim($tenant->contact);

        try {
            /** @var \Illuminate\Http\JsonResponse $response */
            $response = $this->smsService->send($contact, $message);
            $responseData = $response->getData(true);

            if ($response->status() >= 400) {
                throw new \Exception("SMS API returned status " . $response->status() . ": " . json_encode($responseData));
            }

            $bill->update([
                'notified_at' => now(),
                'notification_error' => null,
                'notification_attempts' => $bill->notification_attempts + 1,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("SMS notification failed for tenant {$tenant->name}: " . $e->getMessage());

            $bill->update([
                'notification_error' => $e->getMessage(),
                'notification_attempts' => $bill->notification_attempts + 1,
            ]);

            return false;
        }
    }

    /**
     * Formats the notification message for a bill.
     */
    protected function prepareMessage(Bill $bill): string
    {
        $tenant = $bill->tenant;
        $amount = $bill->grand_total > 0 ? $bill->grand_total : $bill->total_amount;
        $formattedAmount = number_format($amount);

        return "Hello {$tenant->name}, Geta WaterBill Services wishes you a Happy New Year 2026! 🎆 Thank you for doing business with us. Please be informed that your bill for {$bill->month} {$bill->year} is due. Total amount: UGX {$formattedAmount}. Happy New Year! For inquiries, WhatsApp: https://wa.me/256702262806";
    }

    /**
     * Get a summary report for the admin.
     */
    public function getSummaryReport(): array
    {
        $now = now();
        $startOfDay = $now->copy()->startOfDay();

        return [
            'timestamp' => $now->toDateTimeString(),
            'total_tenants' => \App\Models\Tenant::count(),
            'total_bills' => Bill::count(),
            'notified_today' => Bill::whereBetween('notified_at', [$startOfDay, $now])->count(),
            'total_pending' => Bill::whereNull('notified_at')->count(),
            'failed_notifications' => Bill::whereNotNull('notification_error')
                ->whereNull('notified_at')
                ->get()
                ->map(fn($b) => [
                    'tenant' => $b->tenant->name,
                    'reason' => $b->notification_error,
                    'attempts' => $b->notification_attempts,
                ])->toArray(),
        ];
    }
}
