<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetallicPaintDiscountToDealerDiscountsTable extends Migration
{
    public function up(): void
    {
        Schema::table('dealer_discounts', function (Blueprint $table) {
            $table
                ->string('paint_discount')
                ->after('discount')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('dealer_discounts', function (Blueprint $table) {
            $table->dropColumn('paint_discount');
        });
    }
}
