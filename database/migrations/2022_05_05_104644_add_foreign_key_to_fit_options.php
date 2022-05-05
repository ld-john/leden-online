<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToFitOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fit_options', function (Blueprint $table) {
            $table->dropColumn('dealer');
            $table->bigInteger('dealer_id')->nullable()->after('model_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fit_options', function (Blueprint $table) {
            $table->dropColumn('dealer_id');
            $table->string('dealer')->nullable()->after('model_year');

        });
    }
}
