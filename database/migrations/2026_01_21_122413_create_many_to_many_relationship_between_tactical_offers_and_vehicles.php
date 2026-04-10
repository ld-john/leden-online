<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tactical_offers_vehicles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tactical_offer_id')->index();

            $table->unsignedBigInteger('vehicle_id')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tactical_offers_vehicles');
    }
};
