<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CleanOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanOrders {--force : Force the operation without prompting for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove orders if they are still in the recycle bin after a month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoffDate = Carbon::now()->subMonth();

        $query = Order::onlyTrashed()->where('deleted_at', '<', $cutoffDate);
        $count = $query->count();

        if ($count === 0) {
            $this->info('No Orders in the recycle bin require cleaning.');
            return CommandAlias::SUCCESS;
        }

        // Prompt for confirmation unless --force option is passed or running non-interactively
        if (!$this->option('force') && $this->input->isInteractive()) {
            $confirmed = $this->confirm(
                "{$count} orders have been in the recycle bin for more than a month. Would you like to remove them?",
                true
            );

            if (!$confirmed) {
                $this->info('Operation cancelled.');
                return CommandAlias::SUCCESS;
            }
        }

        $this->line('Clearing orders deleted more than a month ago...');

        // Lazy load in chunks to prevent memory spikes while retaining model events
        $this->withProgressBar($query->lazy(), function (Order $order) {
            $order->forceDelete();
        });

        $this->newLine();
        $this->info('Orders cleanup completed.');

        return CommandAlias::SUCCESS;
    }
}
