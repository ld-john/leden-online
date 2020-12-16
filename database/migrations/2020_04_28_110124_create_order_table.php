<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('preferred_name')->default('customer');
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->string('vehicle_type');
            $table->string('vehicle_reg')->nullable();
            $table->string('vehicle_derivative', 400);
            $table->string('vehicle_engine');
            $table->string('vehicle_trans');
            $table->string('vehicle_fuel_type');
            $table->string('vehicle_doors');
            $table->string('vehicle_colour');
            $table->string('vehicle_body');
            $table->string('vehicle_trim');
            $table->bigInteger('broker')->nullable();
            $table->string('broker_order_ref')->nullable();
            $table->string('order_ref')->nullable();
            $table->string('chassis_prefix')->nullable();
            $table->string('chassis')->nullable();
            $table->bigInteger('vehicle_status')->default(1);
            $table->timestamp('due_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->string('model_year')->nullable();
            $table->timestamp('vehicle_registered_on')->nullable();
            $table->bigInteger('dealership')->nullable();
            $table->string('invoice_to')->nullable();
            $table->longText('invoice_to_address')->nullable();
            $table->string('register_to')->nullable();
            $table->longText('register_to_address')->nullable();
            $table->float('list_price', 12, 2)->nullable();
            $table->float('metalic_paint', 8, 2)->nullable();
            $table->float('dealer_discount', 8, 3)->nullable();
            $table->float('manufacturer_discount', 8, 3)->nullable();
            $table->float('total_discount', 8, 3)->nullable();
            $table->float('manufacturer_delivery_cost', 8, 2)->nullable();
            $table->float('first_reg_fee', 8, 2)->nullable();
            $table->float('rfl_cost', 8, 2)->nullable();
            $table->float('onward_delivery', 8, 2)->nullable();
            $table->float('invoice_funder_for', 12, 2)->nullable();
            $table->float('invoice_value', 12, 2)->nullable();
            $table->boolean('show_discount');
            $table->boolean('show_offer');
            $table->boolean('hide_from_broker');
            $table->boolean('hide_from_dealer');
            $table->boolean('show_in_ford_pipeline')->default(0);
            $table->float('invoice_finance', 8, 2)->nullable();
            $table->string('invoice_finance_number')->nullable();
            $table->timestamp('finance_commission_paid')->nullable();
            $table->float('invoice_broker', 8, 2)->nullable();
            $table->string('invoice_broker_number')->nullable();
            $table->timestamp('invoice_broker_paid')->nullable();
            $table->float('commission_broker', 8, 2)->nullable();
            $table->string('commission_broker_number')->nullable();
            $table->timestamp('commission_broker_paid')->nullable();
            $table->string('delivery_address_1', 300)->nullable();
            $table->string('delivery_address_2', 300)->nullable();
            $table->string('delivery_town')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_county')->nullable();
            $table->string('delivery_postcode')->nullable();
            $table->longText('comments')->nullable();
            $table->timestamp('reserved_on')->nullable();
            $table->boolean('admin_accepted')->default(0);
            $table->boolean('dealer_accepted')->default(0);
            $table->boolean('broker_accepted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
