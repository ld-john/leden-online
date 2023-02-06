<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RetypeColumnsOnInvoicesTable2 extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('finance_commission_pay_date')->nullable()->after('finance_commission_pay_date_OLD');
            $table->date('broker_commission_pay_date')->nullable()->after('broker_commission_pay_date_OLD');
            $table->date('broker_pay_date')->nullable()->after('broker_pay_date_OLD');
            $table->date('dealer_pay_date')->nullable()->after('dealer_pay_date_OLD');
            $table->date('fleet_procure_pay_date')->nullable()->after('fleet_procure_pay_date_OLD');
            $table->date('finance_company_bonus_pay_date')->nullable()->after('finance_company_bonus_pay_date_OLD');
            $table->date('ford_bonus_pay_date')->nullable()->after('ford_bonus_pay_date_OLD');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'finance_commission_pay_date', 'broker_commission_pay_date', 'broker_pay_date', 'dealer_pay_date', 'fleet_procure_pay_date', 'finance_company_bonus_pay_date', 'ford_bonus_pay_date'
            ]);
        });
    }
}
