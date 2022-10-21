<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOldDatesFromOrderTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_date_OLD',
                'due_date_OLD',
                'completed_date_OLD',
            ]);
        });
    }
}
