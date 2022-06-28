<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEndeudamientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_endeudamiento', function (Blueprint $table) {
            $table->id();
            $table->integer('activototal')->unsigned();
            $table->integer('pasivototal')->unsigned();
            $table->integer('pasivocorriente')->unsigned();
            $table->integer('patrimoniototal')->unsigned();
            $table->float('endeudamientototal');
            $table->float('leverage');
            $table->float('cortoplazo');
            $table->string('detalle', 255);
            $table->foreignId('idEmpresa')->constrained('tb_configuracion_basica');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_endeudamiento');
    }
}
