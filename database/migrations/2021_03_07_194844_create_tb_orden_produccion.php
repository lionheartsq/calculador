<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrdenProduccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_orden_produccion', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo');
            $table->date('fecha')->format('Y.m.d');
            $table->foreignId('idProducto')->constrained('tb_producto');
            $table->float('cantidad');
            $table->boolean('estado')->default(1);
            $table->foreignId('idOrdenPedido')->constrained('tb_orden_pedido_cliente');
            $table->foreignId('idEmpresa')->constrained('tb_configuracion_basica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_orden_produccion');
    }
}
