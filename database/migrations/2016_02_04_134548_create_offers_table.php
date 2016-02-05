<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{

    const TABLE = 'offers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function(Blueprint $table) {
            $table->increments('id');
            $table->text('href');
            $table->string('olx_id')->unique();
            $table->text('title');
            $table->text('description');
            $table->string('price_string');
            $table->string('date_string');
            $table->string('offer_number')->unique();
            $table->string('cat_path');
            $table->string('phones');
            $table->string('location');
            $table->text('details')->nullable();
            $table->timestamp('created_at_olx')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE);
    }
}
