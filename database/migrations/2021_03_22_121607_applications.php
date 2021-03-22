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
                $table->binary('cv');
                $table->binary('cover_letter');
                $table->boolean('closed');
                $table->date('created');
                $table->unsignedInteger('internship_offers_id');
                $table->unsignedInteger('users_id');
                $table->foreign('internship_offers_id')->references('id')->on('internship_offers');
                $table->foreign('users_id')->references('id')->on('users');
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
