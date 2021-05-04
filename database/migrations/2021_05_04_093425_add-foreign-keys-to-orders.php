<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('dealer_id')->references('id')->on('companies');
            $table->foreign('broker_id')->references('id')->on('companies');
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_vehicle_id_foreign');
            $table->dropForeign('orders_customer_id_foreign');
            $table->dropForeign('orders_dealer_id_foreign');
            $table->dropForeign('orders_broker_id_foreign');
            $table->dropForeign('orders_invoice_id_foreign');

        });
    }
}
