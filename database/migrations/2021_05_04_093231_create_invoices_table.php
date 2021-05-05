<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->string('finance_commission_invoice_number');
            $table->string('broker_invoice_number');
            $table->string('broker_commission_invoice_number');

            $table->float('dealer_discount', 12, 2)->nullable();
            $table->float('manufacturer_discount', 12, 2)->nullable();
            $table->float('manufacturer_delivery_cost', 12, 2)->nullable();
            $table->float('onward_delivery', 12, 2)->nullable();
            $table->float('invoice_funder_for', 12, 2)->nullable();
            $table->float('invoice_value', 12, 2)->nullable();
            $table->float('commission_to_finance_company', 12, 2)->nullable();
            $table->float('commission_to_broker', 12, 2)->nullable();
            $table->float('invoice_value_to_broker', 12, 2)->nullable();

            $table->dateTime('finance_commission_pay_date');
            $table->dateTime('broker_commission_pay_date');
            $table->dateTime('broker_pay_date');

            $table->unsignedBigInteger('invoice_to');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('invoice_to')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
