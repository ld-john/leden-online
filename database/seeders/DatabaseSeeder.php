<?php

namespace Database\Seeders;

use App\Finance\FinanceType;
use App\Finance\InitialPayment;
use App\Finance\Maintenance;
use App\Finance\Mileage;
use App\Finance\Term;
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
