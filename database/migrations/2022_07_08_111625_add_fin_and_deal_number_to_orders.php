<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->string('fin_number')
                ->nullable()
                ->default(65634)
                ->after('comments');
            $table
                ->string('deal_number')
                ->nullable()
                ->default(90191)
                ->after('fin_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('fin_number');
            $table->dropColumn('deal_number');
        });
    }
};
