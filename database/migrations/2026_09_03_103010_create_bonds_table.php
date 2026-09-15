<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bonds', function (Blueprint $table) {
            $table->id();
            $table->float('bond_value')->nullable();
            $table->date('payment_date')->nullable();
            $table->float('refund_value')->nullable();
            $table->date('refund_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonds');
    }
};
