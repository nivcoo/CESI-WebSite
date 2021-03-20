<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Roles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {

                $table->increments('id');
                $table->string('name');
                $table->engine = 'InnoDB';
            });
            DB::table('roles')->insert([
                    ['name' => 'Student'],
                    ['name' => 'Delegate'],
                    ['name' => 'Pilote'],
                    ['name' => 'Admin']
                ]
            );
        }


        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {

                $table->increments('id');
                $table->string('permission');
                $table->engine = 'InnoDB';
            });
            DB::table('permissions')->insert([
                    ['permission' => 'ACCESS_PANEL'],
                    ['permission' => 'ACCESS_SHOW_STUDENT'],
                    ['permission' => 'ACCESS_SHOW_DELEGATE'],
                    ['permission' => 'ACCESS_SHOW_PILOTE'],
                    ['permission' => 'ACCESS_SHOW_ADMIN']
                ]
            );
        }

        if (!Schema::hasTable('permissions_roles')) {
            Schema::create('permissions_roles', function (Blueprint $table) {

                $table->unsignedInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles');
                $table->unsignedInteger('permission_id');
                $table->foreign('permission_id')->references('id')->on('permissions');
                $table->engine = 'InnoDB';
            });
            DB::table('permissions_roles')->insert([
                    ['role_id' => 1, 'permission_id' => 1],
                    ['role_id' => 1, 'permission_id' => 2],
                    ['role_id' => 1, 'permission_id' => 3],
                    ['role_id' => 1, 'permission_id' => 4],
                    ['role_id' => 1, 'permission_id' => 5],
                    ['role_id' => 2, 'permission_id' => 1],
                    ['role_id' => 2, 'permission_id' => 2],
                    ['role_id' => 2, 'permission_id' => 3],
                    ['role_id' => 2, 'permission_id' => 4],
                    ['role_id' => 2, 'permission_id' => 5],
                    ['role_id' => 3, 'permission_id' => 1],
                    ['role_id' => 3, 'permission_id' => 2],
                    ['role_id' => 3, 'permission_id' => 3],
                    ['role_id' => 3, 'permission_id' => 4],
                    ['role_id' => 3, 'permission_id' => 5],

                ]
            );
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permissions_roles');
    }
}
