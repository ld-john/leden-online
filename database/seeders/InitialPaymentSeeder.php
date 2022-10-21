<?php

namespace Database\Seeders;

use App\Finance\InitialPayment;
use Illuminate\Database\Seeder;

class InitialPaymentSeeder extends Seeder
{
    public function run()
    {
        $payments = range(1, 12);
        foreach ($payments as $payment) {
            $initial = new InitialPayment();
            $initial->option = $payment;
            $initial->save();
        }
    }
}
