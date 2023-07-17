<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivedAtToFitOptions extends Migration
{
    public function up(): void
    {
        Schema::table('fit_options', function (Blueprint $table) {
            $table
                ->date('archived_at')
                ->after('option_price')
                ->nullable()
                ->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('fit_options', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
    }
}
