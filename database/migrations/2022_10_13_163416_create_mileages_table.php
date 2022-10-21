<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMileagesTable extends Migration
{
    public function up()
    {
        Schema::create('mileages', function (Blueprint $table) {
            $table->id();
            $table->string('option');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mileages');
    }
}
