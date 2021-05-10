<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminBrokerDealerAccept extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('admin_accepted')->default(0)->after('due_date');
            $table->boolean('dealer_accepted')->default(0)->after('due_date');
            $table->boolean('broker_accepted')->default(0)->after('due_date');
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
	        $table->dropColumn('admin_accepted');
	        $table->dropColumn('dealer_accepted');
	        $table->dropColumn('broker_accepted');
        });
    }
}
