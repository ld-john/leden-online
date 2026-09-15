<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CleanCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanCustomers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove customers permanently if they have been in the recycle bin for over a month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoffDate = Carbon::now()->subMonth();

        $query = Customer::onlyTrashed()->where('deleted_at', '<=', $cutoffDate);
        $count = $query->count();

        if ($count === 0) {
            $this->info('No Customers in recycle bin require cleaning.');
            return CommandAlias::SUCCESS;
        }

        $this->line("Clearing {$count} customers deleted more than a month ago...");

        // Lazy load in chunks to keep memory usage low while preserving model events
        $this->withProgressBar($query->lazy(), function (Customer $customer) {
            $customer->forceDelete();
        });

        $this->newLine();
        $this->info('Customer recycle bin cleanup completed.');

        return CommandAlias::SUCCESS;
    }
}
