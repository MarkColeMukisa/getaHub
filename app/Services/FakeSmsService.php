<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

class FakeSmsService implements SmsServiceInterface
{
    /**
     * Log the SMS message instead of sending it.
     */
    public function send(string $recipient, string $message): \Illuminate\Http\JsonResponse
    {
        Log::info("FAKE SMS to {$recipient}: {$message}");

        return response()->json([
            'status' => 'success',
            'message' => 'Fake SMS logged successfully',
            'data' => [
                'recipient' => $recipient,
                'message' => $message,
                'mode' => 'fake'
            ]
        ]);
    }
}
