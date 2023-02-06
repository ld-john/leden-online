<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RetypeColumnsInVehiclesTablePart2 extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table
                ->date('vehicle_registered_on')
                ->nullable()
                ->after('vehicle_registered_on_OLD');
            $table
                ->date('due_date')
                ->nullable()
                ->after('due_date_OLD');
            $table
                ->date('build_date')
                ->nullable()
                ->after('build_date_OLD');
            $table
                ->date('vehicle_reg_date')
                ->nullable()
                ->after('build_date');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //
        });
    }
}
