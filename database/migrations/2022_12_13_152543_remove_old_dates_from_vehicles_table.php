<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOldDatesFromVehiclesTable extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_registered_on_OLD',
                'due_date_OLD',
                'build_date_OLD',
                'vehicle_reg_date_OLD'
            ]);
        });
    }

}
