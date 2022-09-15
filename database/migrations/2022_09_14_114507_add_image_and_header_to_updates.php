<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageAndHeaderToUpdates extends Migration
{
    public function up()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table
                ->string('header')
                ->nullable()
                ->after('id');
            $table
                ->string('image')
                ->nullable()
                ->after('update_text');
            $table
                ->string('update_type')
                ->default('update')
                ->after('dashboard');
        });
    }

    public function down()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->dropColumn('header');
            $table->dropColumn('image');
            $table->dropColumn('update_type');
        });
    }
}
