<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinanceBrokerIdToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('finance_broker_id')
                ->nullable()
                ->after('broker_id');
            $table
                ->boolean('finance_broker_toggle')
                ->default(false)
                ->after('finance_broker_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('finance_broker_id', 'finance_broker_toggle');
        });
    }
}
