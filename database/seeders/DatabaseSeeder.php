<?php

namespace Database\Seeders;

use App\Models\Finance\FinanceType;
use App\Models\Finance\InitialPayment;
use App\Models\Finance\Maintenance;
use App\Models\Finance\Mileage;
use App\Models\Finance\Term;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        FinanceType::truncate();
        $this->call(FinanceTypeSeeder::class);
        Maintenance::truncate();
        $this->call(MaintenanceSeeder::class);
        Term::truncate();
        $this->call(TermSeeder::class);
        InitialPayment::truncate();
        $this->call(InitialPaymentSeeder::class);
        Mileage::truncate();
        $this->call(MileageSeeder::class);
    }
}
