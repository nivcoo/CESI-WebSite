<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PermissionsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('permissions_users')) {
            Schema::create('permissions_users', function (Blueprint $table) {

                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->unsignedInteger('permission_id');
                $table->foreign('permission_id')->references('id')->on('permissions');
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
        Schema::dropIfExists('permissions_users');
    }
}
