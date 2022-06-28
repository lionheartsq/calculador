<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetalleInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalle_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idProducto')->constrained('tb_gestion_materia_prima');
            $table->foreignId('unidadBase')->constrained('tb_unidad_base');
            $table->integer('cantidadSistema');
            $table->integer('cantidadActual')->nullable();
            $table->integer('diferencia')->nullable();
            $table->string('observacion')->nullable();
            $table->foreignId('idEmpresa')->constrained('tb_configuracion_basica');
            $table->foreignId('idInventario')->constrained('tb_inventario');
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
        Schema::dropIfExists('tb_detalle_inventario');
    }
}
