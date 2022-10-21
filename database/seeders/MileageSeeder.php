<?php

namespace Database\Seeders;

use App\Models\Finance\Mileage;
use Illuminate\Database\Seeder;

class MileageSeeder extends Seeder
{
    public function run()
    {
        $options = range(5000, 50000, 500);
        foreach ($options as $option) {
            $mileage = new Mileage();
            $mileage->option = $option;
            $mileage->save();
        }
    }
}
