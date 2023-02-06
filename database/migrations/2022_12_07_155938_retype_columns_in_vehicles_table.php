<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RetypeColumnsInVehiclesTable extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->renameColumn(
                'vehicle_registered_on',
                'vehicle_registered_on_OLD',
            );
            $table->renameColumn('due_date', 'due_date_OLD');
            $table->renameColumn('build_date', 'build_date_OLD');
            $table->renameColumn('vehicle_reg_date', 'vehicle_reg_date_OLD');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->renameColumn(
                'vehicle_registered_on_OLD',
                'vehicle_registered_on',
            );
            $table->renameColumn('due_date_OLD', 'due_date');
            $table->renameColumn('build_date_OLD', 'build_date');
            $table->renameColumn('vehicle_reg_date_OLD', 'vehicle_reg_date');
        });
    }
}
