<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function checkTable($name)
{
    try {
        $count = DB::table($name)->count();
        echo "[OK] Table '$name' exists, Count: $count\n";
    } catch (\Exception $e) {
        echo "[FAIL] Table '$name' error: " . $e->getMessage() . "\n";
    }
}

echo "--- Database Connectivity & Schema Check ---\n";
echo "Connection: " . DB::connection()->getName() . "\n";
echo "Database: " . DB::connection()->getDatabaseName() . "\n";

checkTable('users');
checkTable('tenants');
checkTable('bills');
checkTable('jobs');
checkTable('failed_jobs');
checkTable('migrations');

echo "\n--- Sample Data ---\n";
try {
    $t = Tenant::first();
    echo "First Tenant: " . ($t ? $t->name : 'NONE') . "\n";
    $b = Bill::first();
    echo "First Bill ID: " . ($b ? $b->id : 'NONE') . "\n";
    $u = User::first();
    echo "First User: " . ($u ? $u->email : 'NONE') . "\n";
} catch (\Exception $e) {
    echo "Data sample error: " . $e->getMessage() . "\n";
}

echo "\n--- Recent Mail/Job Logs (last 5 entries) ---\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $lines = file($logPath);
    $count = 0;
    for ($i = count($lines) - 1; $i >= 0 && $count < 5; $i--) {
        if (strpos($lines[$i], 'VerifyNotificationsJob') !== false || strpos($lines[$i], 'ERROR') !== false) {
            echo trim($lines[$i]) . "\n";
            $count++;
        }
    }
}
