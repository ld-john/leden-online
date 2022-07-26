<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleMetaTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_meta', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('vehicle_meta_vehicle_model', function (
            Blueprint $table,
        ) {
            $table->unsignedBigInteger('vehicle_meta_id');
            $table->unsignedBigInteger('vehicle_model_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_meta');
        Schema::dropIfExists('vehicle_meta_vehicle_model');
    }
}
