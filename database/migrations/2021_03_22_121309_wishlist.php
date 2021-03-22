<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Wishlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wishlist')) {
            Schema::create('wishlist', function (Blueprint $table) {

                $table->foreign('users_id')->references('id')->on('users');
                $table->foreign('internship_offers_id')->references('id')->on('internship_offers');
                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlist');
    }
}
