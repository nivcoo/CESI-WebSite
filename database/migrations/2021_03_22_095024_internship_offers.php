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
        if (!Schema::hasTable('internship_offers')) {
            Schema::create('internship_offers', function (Blueprint $table) {

                $table->increments('id');

                $table->longText('content');
                $table->date('offer_start');
                $table->date('offer_end');
                $table->date('end_date')->nullable($value = true);
                $table->unsignedInteger('societies_id');
                $table->foreign('societies_id')->references('id')->on('societies');
                $table->boolean('archived');
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
        Schema::dropIfExists('internship_offers');
    }
}
