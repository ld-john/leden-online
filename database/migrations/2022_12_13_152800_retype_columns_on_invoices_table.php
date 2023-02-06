<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RetypeColumnsOnInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('finance_commission_pay_date', 'finance_commission_pay_date_OLD');
            $table->renameColumn('broker_commission_pay_date', 'broker_commission_pay_date_OLD');
            $table->renameColumn('broker_pay_date', 'broker_pay_date_OLD');
            $table->renameColumn('dealer_pay_date', 'dealer_pay_date_OLD');
            $table->renameColumn('fleet_procure_pay_date', 'fleet_procure_pay_date_OLD');
            $table->renameColumn('finance_company_bonus_pay_date', 'finance_company_bonus_pay_date_OLD');
            $table->renameColumn('ford_bonus_pay_date', 'ford_bonus_pay_date_OLD');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
}
