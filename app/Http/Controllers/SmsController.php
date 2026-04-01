<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\SmsService;

class SmsController extends Controller
{
    public function send(SmsService $smsService)
    {
        $response = $smsService->send('+256702262806', 'Hello World');

        return response()->json($response);
    }
}
