<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraOrderVehicleFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('vehicles', function (Blueprint $table) {
		    $table->json('factory_fit_options')->nullable()->after('trim');
		    $table->json('dealer_fit_options')->nullable()->after('trim');
	    });
	    Schema::table('orders', function (Blueprint $table) {
		    $table->string('broker_ref')->nullable()->after('order_ref');
		    $table->dateTime('due_date')->nullable()->after('comments');
		    $table->dateTime('delivery_date')->nullable()->after('comments');
		    $table->unsignedBigInteger('registration_company_id')->nullable()->after('broker_id');
		    $table->unsignedBigInteger('invoice_company_id')->nullable()->after('broker_id');

		    $table->foreign('registration_company_id')->references('id')->on('companies');
		    $table->foreign('invoice_company_id')->references('id')->on('companies');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('vehicles', function (Blueprint $table) {
		    $table->dropColumn('factory_fit_options');
		    $table->dropColumn('dealer_fit_options');
	    });
	    Schema::table('orders', function (Blueprint $table) {
		    $table->dropForeign('orders_registration_company_id_foreign');
		    $table->dropForeign('orders_invoice_company_id_foreign');
		    $table->dropColumn('broker_ref');
		    $table->dropColumn('due_date');
		    $table->dropColumn('delivery_date');
		    $table->dropColumn('registration_company_id');
		    $table->dropColumn('invoice_company_id');
	    });
    }
}
