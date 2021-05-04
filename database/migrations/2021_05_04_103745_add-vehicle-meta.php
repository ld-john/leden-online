<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVehicleMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle-types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('make_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle-derivatives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('make_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle-engines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('make_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle-transmissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle-fuels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle-colours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('make_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle-trims', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('make_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle-types');
        Schema::dropIfExists('vehicle-derivatives');
        Schema::dropIfExists('vehicle-engines');
        Schema::dropIfExists('vehicle-transmissions');
        Schema::dropIfExists('vehicle-fuels');
        Schema::dropIfExists('vehicle-colours');
        Schema::dropIfExists('vehicle-trims');
    }
}
