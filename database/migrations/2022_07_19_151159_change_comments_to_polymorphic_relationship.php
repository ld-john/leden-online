<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommentsToPolymorphicRelationship extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('order_id', 'commentable_id');
            $table
                ->string('commentable_type')
                ->nullable()
                ->after('order_id');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {});
    }
}
