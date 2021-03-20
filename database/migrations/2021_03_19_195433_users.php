<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {

                $table->increments('id');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email');
                $table->text('password');
                $table->unsignedInteger('role_id');
                $table->date('birth_date');
                $table->string('remember_token')->nullable($value = true);
                $table->foreign('role_id')->references('id')->on('roles');
                $table->engine = 'InnoDB';
                $table->timestamps();
            });
            DB::table('users')->insert([
                    ['first_name' => 'Admin', 'last_name' => 'ADMIN', 'email' => 'test@admin.fr', 'password' => Hash::make("admin"), 'role_id' => 4, 'birth_date' => '2000-01-01']
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
        Schema::dropIfExists('users');
    }
}
