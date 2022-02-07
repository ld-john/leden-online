<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDealerInvoiceDetailsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->float('invoice_value_from_dealer', 8, 2)->nullable()->after('invoice_value_to_broker');
            $table->string('dealer_invoice_number')->nullable()->after('broker_invoice_number');
            $table->timestamp('dealer_pay_date')->nullable()->after('broker_pay_date');
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
            $table->dropColumn('invoice_dealer');
            $table->dropColumn('invoice_dealer_number');
            $table->dropColumn('invoice_dealer_paid');
        });
    }
}
