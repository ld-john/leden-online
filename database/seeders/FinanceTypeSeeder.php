<?php

namespace Database\Seeders;

use App\Finance\FinanceType;
use Illuminate\Database\Seeder;

class FinanceTypeSeeder extends Seeder
{
    public function run()
    {
        $finance = [
            'Regulated CH (Individual & Sole Trader)',
            'Non-Regulated CH (Limited Company & Large Partnership)',
            'Finance Lease',
        ];

        foreach ($finance as $type) {
            $finance_type = new FinanceType();
            $finance_type->option = $type;
            $finance_type->save();
        }
    }
}
