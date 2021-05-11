<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDealerIdToVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('dealer_id')->default(1)->after('vehicle_status');

	        $table->foreign('dealer_id')->references('id')->on('companies');
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
	        $table->dropForeign('vehicles_dealer_id_foreign');
	        $table->dropColumn('dealer_id');
        });
    }
}
