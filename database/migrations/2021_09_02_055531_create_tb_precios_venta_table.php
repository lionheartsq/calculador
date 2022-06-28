<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPreciosVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_precios_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idProducto')->constrained('tb_producto');
            $table->integer('costo')->unsigned();
            $table->float('porcentaje');
            $table->integer('cifunitario')->unsigned();
            $table->integer('costosfijos')->unsigned();
            $table->integer('materiaprima')->unsigned();
            $table->integer('manodeobradirecta')->unsigned();
            $table->integer('preciodeventa')->unsigned();
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
        Schema::dropIfExists('tb_precios_venta');
    }
}
