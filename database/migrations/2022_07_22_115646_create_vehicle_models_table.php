<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleModelsTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('manufacturer_id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_models');
    }
}
