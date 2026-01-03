<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Create a new class instance.
     */
    public function send($recipient, $message): \Illuminate\Http\JsonResponse
    {
        $response = Http::withBasicAuth(
            config('services.marz.key'),
            config('services.marz.secret')
        )->post(config('services.marz.url'), [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        Log::info("SMS sending to {$recipient}: " . $response->body());

        return response()->json($response->json());
    }
}
