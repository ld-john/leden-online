<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOldDatesFromInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'finance_commission_pay_date_OLD',
                'broker_commission_pay_date_OLD',
                'broker_pay_date_OLD',
                'dealer_pay_date_OLD',
                'fleet_procure_pay_date_OLD',
                'finance_company_bonus_pay_date_OLD',
                'ford_bonus_pay_date_OLD',
            ]);
        });
    }
}
