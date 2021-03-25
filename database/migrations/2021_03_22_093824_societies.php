<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Societies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('societies')) {
            Schema::create('societies', function (Blueprint $table) {

                $table->increments('id');
                $table->string('name', 50);
                $table->string('activity_field', 50);
                $table->string('address', 50);
                $table->integer('postal_code');
                $table->string('city', 50);
                $table->integer('cesi_interns')->nullable($value = true);
                $table->integer('evaluation')->nullable($value = true);
                $table->engine = 'InnoDB';
                $table->timestamps();
            });
            DB::table('societies')->insert([
                    ['name' => 'Society 1', 'activity_field' => "IT", 'address' => "8 Rue des Frères Charles et Alcide d'Orbigny", "postal_code" => 64000, "city" => "Pau", "cesi_interns" => 8, "evaluation" => 15]
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
        Schema::dropIfExists('societies');
    }
}
