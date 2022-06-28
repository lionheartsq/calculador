<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbKardexProductoTerminadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_kardex_producto_terminado', function (Blueprint $table) {

            $table->id();
            $table->date('fecha');
            $table->string('detalle', 255);
            $table->float('cantidad');
            $table->float('precio');
            $table->float('cantidadSaldos');
            $table->float('precioSaldos');
            $table->string('observaciones', 255);
            $table->foreignId('idEmpleado')->constrained('tb_empleado');
            $table->foreignId('idDocumentos')->constrained('tb_documentos');
            $table->foreignId('idProducto')->constrained('tb_producto');
            $table->boolean('tipologia')->default(1);
            $table->foreignId('idEmpresa')->constrained('tb_configuracion_basica');
            // tipo 1 entrada tipo 0 salida
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_kardex_producto_terminado');
    }
}
