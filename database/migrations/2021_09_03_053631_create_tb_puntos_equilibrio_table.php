<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPuntosEquilibrioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_puntos_equilibrio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idProducto')->constrained('tb_precios_venta');
            $table->integer('preciodeventa')->unsigned();
            $table->integer('costosfijos')->unsigned();
            $table->integer('gastosfijos')->unsigned();
            $table->integer('materiaprima')->unsigned();
            $table->integer('manodeobradirecta')->unsigned();
            $table->float('puntodeequilibrio');
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
        Schema::dropIfExists('tb_puntos_equilibrio');
    }
}
