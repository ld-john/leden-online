<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->id();

            $table->string('update_text')->nullable();
            $table->string('dashboard')->default('broker');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('updates');
    }
}
