<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CleanNoOrderCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'noOrderCustomers {--force : Force the operation without prompting for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove customers created over 3 months ago who have never placed an order';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoffDate = Carbon::now()->subMonths(3);

        // Filter directly in the database query using doesntHave()
        $query = Customer::where('created_at', '<=', $cutoffDate)
            ->doesntHave('orders');

        $count = $query->count();

        if ($count === 0) {
            $this->info('All customers on the system for more than three months have orders.');
            return CommandAlias::SUCCESS;
        }

        // Handle confirmation for terminal usage vs automated background runs
        if (! $this->option('force') && $this->input->isInteractive()) {
            $confirmed = $this->confirm(
                "{$count} customers have made no orders and have been in the system for 3 months or more. Would you like to remove them?",
                true
            );

            if (! $confirmed) {
                $this->info('Operation cancelled.');
                return CommandAlias::SUCCESS;
            }
        }

        $this->line('Clearing customers with no orders...');

        // Process in chunks to maintain low memory footprint while allowing model events to fire
        $this->withProgressBar($query->lazy(), function (Customer $customer) {
            $customer->delete();
        });

        $this->newLine();
        $this->info('Customer cleanup completed successfully.');

        return CommandAlias::SUCCESS;
    }
}
