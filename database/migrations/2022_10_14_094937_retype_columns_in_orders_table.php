<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RetypeColumnsInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('delivery_date', 'delivery_date_OLD');
            $table->renameColumn('due_date', 'due_date_OLD');
            $table->renameColumn('completed_date', 'completed_date_OLD');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('delivery_date_OLD', 'delivery_date');
            $table->renameColumn('due_date_OLD', 'due_date');
            $table->renameColumn('completed_date_OLD', 'completed_date');
        });
    }
}
