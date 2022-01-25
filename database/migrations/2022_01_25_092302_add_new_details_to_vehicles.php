<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDetailsToVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('broker_id')->after('dealer_id')->nullable();
            $table->string('orbit_number')->after('vehicle_status')->unique()->nullable();

            $table->foreign('broker_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicles_broker_id_foreign');
            $table->dropColumn('broker_id');
            $table->dropColumn('orbit_number');
        });
    }
}
