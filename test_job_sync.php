<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Jobs\VerifyNotificationsJob;
use App\Services\NotificationService;

echo "Attempting to run VerifyNotificationsJob sync...\n";

try {
    $service = app(NotificationService::class);
    $job = new VerifyNotificationsJob();
    $job->handle($service);
    echo "Job completed successfully (Handle method finished).\n";
} catch (\Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace trace (first 5 lines):\n";
    echo implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 0, 5)) . "\n";
}
