<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Middleware\UpdateExpiredStatusMiddleware;

class MonitorExpiredProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:monitor-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor product expiration dates and update status + generate notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting inventory expiration check...');
        $middleware = new UpdateExpiredStatusMiddleware();
        $middleware->updateExpiryStatuses();
        $this->info('Inventory expiration check completed.');
        return Command::SUCCESS;
    }
}
