<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('details_parameter_id');
            $table->string('value')->unique();
            $table->string('export_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('details_values');
    }
}
