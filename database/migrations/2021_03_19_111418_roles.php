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
        Schema::create('roles', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->text('permissions');
            $table->engine = 'InnoDB';
            $table->timestamps();
        });
        DB::table('roles')->insert([
                ['name' => 'Student', 'permissions' => ''],
                ['name' => 'Delegate', 'permissions' => ''],
                ['name' => 'Pilote', 'permissions' => ''],
                ['name' => 'Admin', 'permissions' => '']
            ]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank');
    }
}
