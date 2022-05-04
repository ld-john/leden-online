<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToFitOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fit_options', function (Blueprint $table) {
            $table->string('model')->nullable()->after('option_name');
            $table->string('model_year')->nullable()->after('model');
            $table->string('dealer')->nullable()->after('model_year');
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
            $table->dropColumn('model');
            $table->dropColumn('model_year');
            $table->dropColumn('dealer');
        });
    }
}
