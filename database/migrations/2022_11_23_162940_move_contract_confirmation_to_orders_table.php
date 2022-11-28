<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveContractConfirmationToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->boolean('contract_confirmation')
                ->after('broker_ref')
                ->default(false);
        });
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('delivery_authorised');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('contract_confirmation');
        });
        Schema::table('deliveries', function (Blueprint $table) {
            $table
                ->boolean('delivery_authorised')
                ->after('funder_confirmation')
                ->default(false);
        });
    }
}
