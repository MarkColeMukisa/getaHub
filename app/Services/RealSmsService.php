<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SmsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RealSmsService implements SmsServiceInterface
{
    /**
     * Send a real SMS message via service provider.
     */
    public function send(string $recipient, string $message): JsonResponse
    {
        $key = config('services.marz.key');
        $secret = config('services.marz.secret');
        $url = config('services.marz.url');

        if (empty($key) || empty($secret)) {
            throw new RuntimeException('MARZ SMS credentials (MARZ_API_KEY / MARZ_API_SECRET) are not configured in .env');
        }

        $response = Http::withBasicAuth($key, $secret)->post($url, [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        Log::info("Real SMS sending to {$recipient}: ".($response->successful() ? 'Success' : 'Failed').' - '.$response->body());

        return response()->json($response->json(), $response->status());
    }
}
