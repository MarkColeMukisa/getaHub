<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\Bill;

echo "--- Tenant 4 Check ---\n";
$t = Tenant::find(4);
if ($t) {
    echo "ID: " . $t->id . "\n";
    echo "Name: " . $t->name . "\n";
    echo "Contact: " . $t->contact . "\n";
    $lb = $t->latestBill;
    if ($lb) {
        echo "Latest Bill ID: " . $lb->id . "\n";
        echo "Month/Year: " . $lb->month . " " . $lb->year . "\n";
        echo "Grand Total: " . $lb->grand_total . "\n";
        echo "Notified At: " . ($lb->notified_at ?: 'NULL') . "\n";
        echo "Notification Error: " . ($lb->notification_error ?: 'None') . "\n";
        echo "Attempts: " . $lb->notification_attempts . "\n";
    } else {
        echo "No latest bill found.\n";
    }
} else {
    echo "Tenant 4 not found.\n";
}

echo "\n--- Pending Bills Check ---\n";
$pending = Bill::whereNull('notified_at')->get();
echo "Count: " . $pending->count() . "\n";
foreach ($pending as $b) {
    echo "Bill ID: " . $b->id . " | Tenant: " . ($b->tenant->name ?? 'Unknown') . " | Error: " . ($b->notification_error ?? 'None') . " | Attempts: " . $b->notification_attempts . "\n";
}

echo "\n--- All Bills Check (Limit 10) ---\n";
$all = Bill::latest()->limit(10)->get();
foreach ($all as $b) {
    echo "ID: " . $b->id . " | T: " . ($b->tenant->name ?? 'N/A') . " | Notified: " . ($b->notified_at ?: 'No') . "\n";
}

echo "\n--- Queue Jobs Check ---\n";
try {
    $jobs = \DB::table('jobs')->get();
    echo "Jobs in queue: " . $jobs->count() . "\n";
    foreach ($jobs as $j) {
        echo "ID: " . $j->id . " | Queue: " . $j->queue . " | Attempts: " . $j->attempts . "\n";
        $payload = json_decode($j->payload, true);
        echo "   Job Name: " . ($payload['displayName'] ?? 'Unknown') . "\n";
    }
} catch (\Exception $e) {
    echo "Error checking jobs: " . $e->getMessage() . "\n";
}

echo "\n--- Failed Jobs Check ---\n";
try {
    $failed = \DB::table('failed_jobs')->get();
    echo "Failed jobs count: " . $failed->count() . "\n";
    foreach ($failed as $fj) {
        echo "ID: " . $fj->id . " | Class: " . $fj->command_name . " | Failed At: " . $fj->failed_at . "\n";
    }
} catch (\Exception $e) {
    echo "Error checking failed jobs: " . $e->getMessage() . "\n";
}

echo "\n--- Transaction & Lock Test ---\n";
try {
    \DB::transaction(function () {
        echo "Transaction started...\n";
        $job = \DB::table('jobs')->where('queue', 'default')->lockForUpdate()->first();
        if ($job) {
            echo "Job ID " . $job->id . " locked for update.\n";
            \DB::table('jobs')->where('id', $job->id)->update(['reserved_at' => time(), 'attempts' => $job->attempts + 1]);
            echo "Job ID " . $job->id . " updated successfully within transaction.\n";
        } else {
            echo "No job found to lock.\n";
        }
    });
    echo "Transaction committed.\n";
} catch (\Exception $e) {
    echo "Caught Exception in Transaction: " . $e->getMessage() . "\n";
    if ($e instanceof \Illuminate\Database\QueryException) {
        echo "SQL: " . $e->getSql() . "\n";
    }
}
