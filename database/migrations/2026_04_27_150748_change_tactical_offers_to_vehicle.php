<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Add the vehicle_id column and make order_id nullable
        Schema::table('order_tactical_offer', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_id')->nullable()->after('id')->index();
            $table->unsignedBigInteger('order_id')->nullable()->change();
        });

        // 2. Data Migration: Copy Vehicle ID from Orders to the Pivot Table
        // We look up the vehicle_id assigned to the order_id currently in the pivot table
        DB::table('order_tactical_offer')
            ->join('orders', 'order_tactical_offer.order_id', '=', 'orders.id')
            ->update([
                'order_tactical_offer.vehicle_id' => DB::raw('lo_orders.vehicle_id')
            ]);

        // 3. Cleanup: Now that data is migrated, make vehicle_id NOT NULL if desired
        Schema::table('order_tactical_offer', function (Blueprint $table) {

            // 4. Rename table to reflect new logic
            $table->rename('tactical_offer_vehicle');
        });

    }
};
