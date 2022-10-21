<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RetypeColumnsInOrdersTablePart2 extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->date('delivery_date')
                ->nullable()
                ->after('delivery_date_OLD');
            $table
                ->date('due_date')
                ->nullable()
                ->after('due_date_OLD');
            $table
                ->date('completed_date')
                ->nullable()
                ->after('completed_date_OLD');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_date', 'due_date', 'completed_date']);
        });
    }
}
