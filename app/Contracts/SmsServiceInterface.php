<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface SmsServiceInterface
{
    /**
     * Send an SMS message to a recipient.
     */
    public function send(string $recipient, string $message): JsonResponse;
}
