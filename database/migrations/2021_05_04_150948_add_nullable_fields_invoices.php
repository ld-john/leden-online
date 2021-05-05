<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableFieldsInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
	        $table->string('finance_commission_invoice_number')->nullable()->change();
	        $table->string('broker_invoice_number')->nullable()->change();
	        $table->string('broker_commission_invoice_number')->nullable()->change();

	        $table->dateTime('finance_commission_pay_date')->nullable()->change();
	        $table->dateTime('broker_commission_pay_date')->nullable()->change();
	        $table->dateTime('broker_pay_date')->nullable()->change();
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
	        $table->string('finance_commission_invoice_number')->change();
	        $table->string('broker_invoice_number')->change();
	        $table->string('broker_commission_invoice_number')->change();

	        $table->dateTime('finance_commission_pay_date')->change();
	        $table->dateTime('broker_commission_pay_date')->change();
	        $table->dateTime('broker_pay_date')->change();
        });
    }
}
