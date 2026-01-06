<?php

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RealSmsService implements SmsServiceInterface
{
    /**
     * Send a real SMS message via service provider.
     */
    public function send(string $recipient, string $message): \Illuminate\Http\JsonResponse
    {
        $response = Http::withBasicAuth(
            config('services.marz.key'),
            config('services.marz.secret')
        )->post(config('services.marz.url'), [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        Log::info("Real SMS sending to {$recipient}: " . ($response->successful() ? 'Success' : 'Failed') . " - " . $response->body());

        return response()->json($response->json(), $response->status());
    }
}
