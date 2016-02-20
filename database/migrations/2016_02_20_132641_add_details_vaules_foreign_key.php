<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsVaulesForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('details_values', function (Blueprint $table) {
            $table->foreign('details_parameter_id', 'details_value_details_parameter_fk')
                ->references('id')
                ->on('details_parameters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('details_values', function (Blueprint $table) {
            $table->dropForeign('details_value_details_parameter_fk');
        });
    }
}
