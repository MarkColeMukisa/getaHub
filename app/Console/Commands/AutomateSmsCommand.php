<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutomateSmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:automate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually trigger the background SMS notification process';

    /**
     * Execute the console command.
     */
    public function handle(\App\Jobs\AutomateSmsNotificationsJob $job): void
    {
        $this->info('Starting SMS automation process...');

        $job->handle(app(\App\Services\NotificationService::class));

        $this->info('SMS automation process completed successfully.');
    }
}
