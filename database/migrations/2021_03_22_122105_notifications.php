<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {

                $table->increments('id');
                $table->integer('type');
                $table->string('content', 255);
                $table->boolean('seen');
                $table->date('created');
                $table->unsignedInteger('users_id');
                $table->unsignedInteger('applications_id');
                $table->foreign('users_id')->references('id')->on('users');
                $table->foreign('applications_id')->references('id')->on('applications');
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
        Schema::dropIfExists('notifications');
    }
}