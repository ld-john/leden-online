<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullAddressToDeliveries extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->renameColumn('delivery_address', 'delivery_address1');
            $table
                ->string('delivery_address2')
                ->nullable()
                ->after('delivery_address');
            $table
                ->string('delivery_town')
                ->nullable()
                ->after('delivery_address2');
            $table
                ->string('delivery_city')
                ->nullable()
                ->after('delivery_town');
            $table
                ->string('delivery_postcode')
                ->nullable()
                ->after('delivery_city');
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {});
    }
}
