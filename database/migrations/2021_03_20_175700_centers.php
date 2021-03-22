<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Centers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('centers')) {
            Schema::create('centers', function (Blueprint $table) {

                $table->increments('id');
                $table->string('city');
                $table->integer('postal_code');
                $table->string('address');
                $table->engine = 'InnoDB';
            });
            DB::table('centers')->insert([
                    ['city' => 'Pau', 'postal_code' => 64000, 'address' => "8 Rue des Frères Charles et Alcide d'Orbigny"]
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
        Schema::dropIfExists('centers');
    }
}
