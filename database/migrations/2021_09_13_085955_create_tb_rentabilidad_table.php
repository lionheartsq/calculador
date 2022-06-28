<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRentabilidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rentabilidad', function (Blueprint $table) {
            $table->id();
            $table->integer('utilidadbruta')->unsigned();
            $table->integer('utilidadoperacional')->unsigned();
            $table->integer('utilidadneta')->unsigned();
            $table->integer('ingresostotales')->unsigned();
            $table->float('margenbruto');
            $table->float('margenoperacional');
            $table->float('margenneto');
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
        Schema::dropIfExists('tb_rentabilidad');
    }
}
