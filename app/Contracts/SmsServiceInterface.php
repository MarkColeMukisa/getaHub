<?php

namespace App\Contracts;

interface SmsServiceInterface
{
    /**
     * Send an SMS message to a recipient.
     *
     * @param string $recipient
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(string $recipient, string $message): \Illuminate\Http\JsonResponse;
}
