<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 300);
            $table->string('company_address1', 400);
            $table->string('company_address2', 400)->nullable();
            $table->string('company_city');
            $table->string('company_county');
            $table->string('company_country')->nullable();
            $table->string('company_postcode');
            $table->string('company_email');
            $table->string('company_phone')->nullable();
            $table->string('company_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
