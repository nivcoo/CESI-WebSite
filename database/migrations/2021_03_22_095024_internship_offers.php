<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InternshipOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('intership_offers')) {
            Schema::create('intership_offers', function (Blueprint $table) {

                $table->increments('id', 8);
                $table->boolean('archived');
                $table->longText('content');
                $table->date('offer_start');
                $table->date('offer_end');
                $table->date('end_date')->nullable($value = true);
                $table->date('created');
                $table->foreign('societies_id')->references('id')->on('societies');
                $table->engine = 'InnoDB';
                $table->timestamps();
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
        Schema::dropIfExists('intership_offers');
    }
}
