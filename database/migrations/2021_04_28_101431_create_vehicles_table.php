<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vehicle_status')->default(1);
            $table->string('reg')->nullable();
            $table->string('model_year')->nullable();
            $table->string('make');
            $table->string('model');
            $table->string('derivative', 400);
            $table->string('engine');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->string('doors');
            $table->string('colour');
            $table->string('body');
            $table->string('trim');
            $table->string('chassis_prefix')->nullable();
            $table->string('chassis')->nullable();
            $table->string('type');
            $table->float('metallic_paint', 8, 2)->nullable();
            $table->float('list_price', 12, 2)->nullable();
            $table->float('first_reg_fee', 8, 2)->nullable();
            $table->float('rfl_cost', 8, 2)->nullable();
            $table->float('onward_delivery', 8, 2)->nullable();
            $table->timestamp('vehicle_registered_on')->nullable();
            $table->boolean('hide_from_broker');
            $table->boolean('hide_from_dealer');
            $table->boolean('show_in_ford_pipeline')->default(0);
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
        Schema::dropIfExists('vehicles');
    }
}
