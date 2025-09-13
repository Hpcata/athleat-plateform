<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckRecurringPaymentStatus;

class CheckRecurringPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:check-payments {--sync : Run synchronously instead of queuing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check recurring payment status and cancel overdue subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting recurring payment status check...');

        if ($this->option('sync')) {
            // Run synchronously
            $this->info('Running synchronously...');
            $job = new CheckRecurringPaymentStatus();
            $job->handle();
            $this->info('Recurring payment check completed synchronously.');
        } else {
            // Queue the job
            $this->info('Queuing recurring payment check job...');
            CheckRecurringPaymentStatus::dispatch();
            $this->info('Recurring payment check job has been queued.');
        }
    }
}