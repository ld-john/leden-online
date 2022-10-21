<?php

namespace Database\Seeders;

use App\Models\Finance\Maintenance;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run()
    {
        $options = ['Funder', 'Customer'];
        foreach ($options as $option) {
            $maintenance = new Maintenance();
            $maintenance->option = $option;
            $maintenance->save();
        }
    }
}
