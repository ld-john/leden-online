<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinanceInformationToOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('finance_type')
                ->nullable()
                ->after('deal_number');
            $table
                ->unsignedBigInteger('maintenance')
                ->nullable()
                ->after('finance_type');
            $table
                ->unsignedBigInteger('term')
                ->nullable()
                ->after('maintenance');
            $table
                ->unsignedBigInteger('initial_payment')
                ->nullable()
                ->after('term');
            $table
                ->boolean('terminal_pause')
                ->nullable()
                ->after('initial_payment');
            $table
                ->unsignedBigInteger('mileage')
                ->nullable()
                ->after('terminal_pause');
            $table
                ->string('rental')
                ->nullable()
                ->after('mileage');
            $table
                ->string('maintenance_rental')
                ->nullable()
                ->after('rental');
            $table
                ->date('renewal_date')
                ->nullable()
                ->after('maintenance_rental');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
