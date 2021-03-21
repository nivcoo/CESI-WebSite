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
                $table->string('definition');
                $table->engine = 'InnoDB';
            });
            DB::table('permissions')->insert([
                    ['permission' => 'ACCESS_PANEL', "definition" => "Access to the Panel"],
                    ['permission' => 'ACCESS_PERMISSIONS', "definition" => "Access to the Permissions page"],
                    ['permission' => 'USERS_SHOW_STUDENT', "definition" => "Show Students"],
                    ['permission' => 'USERS_ADD_STUDENT', "definition" => "Add a new Student"],
                    ['permission' => 'USERS_EDIT_STUDENT', "definition" => "Edit a Student"],
                    ['permission' => 'USERS_DELETE_STUDENT', "definition" => "Delete a Student"],
                    ['permission' => 'USERS_SHOW_DELEGATE', "definition" => "Show Delegates"],
                    ['permission' => 'USERS_ADD_DELEGATE', "definition" => "Add a new Delegates"],
                    ['permission' => 'USERS_EDIT_DELEGATE', "definition" => "Edit a Delegates"],
                    ['permission' => 'USERS_DELETE_DELEGATE', "definition" => "Delete a Delegates"],
                    ['permission' => 'USERS_SHOW_PILOTE', "definition" => "Show Pilote"],
                    ['permission' => 'USERS_ADD_PILOTE', "definition" => "Add a new Pilote"],
                    ['permission' => 'USERS_EDIT_PILOTE', "definition" => "Edit a Pilote"],
                    ['permission' => 'USERS_DELETE_PILOTE', "definition" => "Delete a Pilote"],
                    ['permission' => 'USERS_SHOW_ADMIN', "definition" => "Show Admins"],
                    ['permission' => 'USERS_ADD_ADMIN', "definition" => "Add a new Admin"],
                    ['permission' => 'USERS_EDIT_ADMIN', "definition" => "Edit an Admin"],
                    ['permission' => 'USERS_DELETE_ADMIN', "definition" => "Delete an Admin"]
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
