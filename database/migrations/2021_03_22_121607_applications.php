<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Applications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {

                $table->increments('id');
                $table->integer('state');
                $table->binary('cv_path');
                $table->binary('cover_letter_path');
                $table->boolean('closed');
                $table->unsignedInteger('internship_offer_id');
                $table->unsignedInteger('user_id');
                $table->foreign('internship_offer_id')->references('id')->on('internship_offers');
                $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('applications');
    }
}
