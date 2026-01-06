<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "Testing mail delivery to joegapp256@gmail.com...\n";

try {
    Mail::raw('This is a test email to verify SMTP configuration.', function ($message) {
        $message->to('joegapp256@gmail.com')->subject('Geta Test Mail');
    });
    echo "Mail command sent successfully. Check your inbox/logs.\n";
} catch (\Exception $e) {
    echo "Mail Error: " . $e->getMessage() . "\n";
    Log::error("Manual Mail Test Error: " . $e->getMessage());
}
