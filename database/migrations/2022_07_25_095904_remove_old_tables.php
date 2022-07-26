<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOldTables extends Migration
{
    public function up()
    {
        Schema::dropIfExists('vehicle-bodies');
        Schema::dropIfExists('company');
        Schema::dropIfExists('fit_options_connector');
        Schema::dropIfExists('invoice_companies');
        Schema::dropIfExists('orderlegacy');
        Schema::dropIfExists('registration_companies');
    }
}
