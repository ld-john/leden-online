<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CleanVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-vehicles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Vehicles if they are still in the recycle bin after a month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoffDate = Carbon::now()->subMonth();

        $query = Vehicle::onlyTrashed()->where('deleted_at', '<', $cutoffDate);
        $count = $query->count();

        if ($count === 0) {
            $this->info('No vehicles in recycle bin require cleaning');
            return CommandAlias::SUCCESS;
        }

        $this->line("Clearing {$count} vehicles deleted more than a month ago...");

        $this->withProgressBar($query->lazy(), function (Vehicle $vehicle) {
            $vehicle->forceDelete();
        });
        $this->newLine();
        $this->info('Recycle bin cleanup completed.');

        return CommandAlias::SUCCESS;
    }
}
