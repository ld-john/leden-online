<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommentsToPolymorphicRelationship extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('order_id', 'commentable_id');
            $table
                ->string('commentable_type')
                ->nullable()
                ->after('commentable_id');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {});
    }
}
