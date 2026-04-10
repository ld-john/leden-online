<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tactical_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('manufacturer_id')->nullable()->after('value');
            $table->string('applicable_model')->nullable()->after('manufacturer_id');
        });

        Schema::dropIfExists('tactical_offers_vehicles');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
