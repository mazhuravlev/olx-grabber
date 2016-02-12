<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvalidPhonesForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invalid_phones', function (Blueprint $table) {
            $table->foreign('offer_id', 'invalid_phone_offer_fk')->references('id')->on('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invalid_phones', function (Blueprint $table) {
            $table->dropForeign('invalid_phone_offer_fk');
        });
    }

}
