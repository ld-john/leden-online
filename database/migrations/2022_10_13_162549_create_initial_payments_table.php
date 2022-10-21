<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('initial_payments', function (Blueprint $table) {
            $table->id();

            $table->string('option');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initial_payments');
    }
}
