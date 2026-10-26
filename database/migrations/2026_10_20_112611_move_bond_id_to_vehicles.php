<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('bond_id')->nullable()
                ->constrained()->nullOnDelete();
        });

        DB::table('orders')
            ->join('bonds', 'bonds.id', '=', 'orders.bond_id')
            ->whereNotNull('orders.bond_id')
            ->orderBy('orders.id')
            ->select('orders.id', 'orders.vehicle_id', 'orders.bond_id')
            ->each(function ($order) {
                DB::table('vehicles')
                    ->where('id', $order->vehicle_id)
                    ->update(['bond_id' => $order->bond_id]);
            });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bond_id'); // Option A
            // $table->dropColumn('bond_id');            // Option B
        });
    }
};
