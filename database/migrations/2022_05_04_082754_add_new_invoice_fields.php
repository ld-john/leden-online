<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewInvoiceFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->float('fleet_procure_invoice', 8, 2)->nullable()->after('invoice_value_from_dealer');
            $table->string('fleet_procure_invoice_number')->nullable()->after('broker_commission_invoice_number');
            $table->timestamp('fleet_procure_pay_date')->nullable()->after('dealer_pay_date');
            $table->float('finance_company_bonus_invoice', 8, 2)->nullable()->after('fleet_procure_invoice');
            $table->string('finance_company_bonus_invoice_number')->nullable()->after('fleet_procure_invoice_number');
            $table->timestamp('finance_company_bonus_pay_date')->nullable()->after('fleet_procure_pay_date');
            $table->float('ford_bonus', 8, 2)->nullable()->after('finance_company_bonus_invoice');
            $table->timestamp('ford_bonus_pay_date')->nullable()->after('finance_company_bonus_pay_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('fleet_procure_invoice');
            $table->dropColumn('fleet_procure_invoice_number');
            $table->dropColumn('fleet_procure_pay_date');
            $table->dropColumn('finance_company_bonus_invoice');
            $table->dropColumn('finance_company_bonus_invoice_number');
            $table->dropColumn('finance_company_bonus_pay_date');
            $table->dropColumn('ford_bonus');
            $table->dropColumn('ford_bonus_pay_date');
        });
    }
}
