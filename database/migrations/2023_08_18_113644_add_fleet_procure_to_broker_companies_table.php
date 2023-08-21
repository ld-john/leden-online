<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFleetProcureToBrokerCompaniesTable extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table
                ->boolean('fleet_procure_member')
                ->default(false)
                ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('fleet_procure_member');
        });
    }
}
