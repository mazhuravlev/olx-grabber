<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('parameter')->unique();
            $table->string('export_property')->nullable();
            $table->boolean('is_integer_field')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('details_parameters');
    }
}
