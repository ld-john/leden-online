<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DatabaseClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'databaseClean {--force : Force execution without interactive prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all scheduled database cleanup tasks (Orders, Vehicles, Customers)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting automated database cleanup routine...');
        $this->newLine();

        $forceOption = $this->option('force') || ! $this->input->isInteractive();
        $params = $forceOption ? ['--force' => true] : [];

        // 1. Clean soft-deleted orders
        $this->components->info('Running order cleanup...');
        $this->call('cleanOrders', $params);

        // 2. Clean soft-deleted vehicles
        $this->components->info('Running vehicle cleanup...');
        $this->call('cleanVehicle');

        // 3. Clean soft-deleted customers
        $this->components->info('Running customer cleanup...');
        $this->call('cleanCustomers');

        // 4. Clean stale customers with no orders
        $this->components->info('Running stale no-order customer cleanup...');
        $this->call('noOrderCustomers', $params);

        $this->newLine();
        $this->info('All database cleanup operations completed successfully.');

        return CommandAlias::SUCCESS;
    }
}
