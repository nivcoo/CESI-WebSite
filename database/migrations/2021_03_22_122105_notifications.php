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
                $table->string('type', 50);
                $table->string('content', 255);
                $table->boolean('seen')->default('0');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('application_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('application_id')->references('id')->on('applications');
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
