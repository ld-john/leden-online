<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFunderConfirmationToDeliveries extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table
                ->string('funder_confirmation')
                ->nullable()
                ->after('contact_number');
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('funder_confirmation');
        });
    }
}
