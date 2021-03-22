<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Promotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('promotion');
                $table->string('speciality');
                $table->engine = 'InnoDB';
            });
            DB::table('promotions')->insert([
                ['promotion' => 'CPIA1', 'speciality' => "global"],
                ['promotion' => 'CPIA1', 'speciality' => "it"],
                ['promotion' => 'CPIA2', 'speciality' => "global"],
                ['promotion' => 'CPIA2', 'speciality' => "it"]

            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
