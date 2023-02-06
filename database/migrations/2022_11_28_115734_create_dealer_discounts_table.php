<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('dealer_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('dealer_id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dealer_discounts');
    }
}
