<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOfferPhonesForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_phone', function (Blueprint $table) {
            $table->foreign('offer_id')->references('id')->on('offers');
            $table->foreign('phone_id')->references('id')->on('phones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_phone', function (Blueprint $table) {
            $table->dropForeign('offer_phone_offer_id_foreign');
            $table->dropForeign('offer_phone_phone_id_foreign');
        });
    }
}
