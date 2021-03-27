<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicationDiscussions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('application_discussions')) {
            Schema::create('application_discussions', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('application_id');
                $table->unsignedInteger('user_id');
                $table->string('file_name')->nullable();
                $table->string('file_path')->nullable();
                $table->string('content')->nullable();
                $table->foreign('application_id')->references('id')->on('applications');
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
        Schema::dropIfExists('application_discussions');
    }
}
