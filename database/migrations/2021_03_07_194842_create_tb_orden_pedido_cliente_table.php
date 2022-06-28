<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrdenPedidoClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_orden_pedido_cliente', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo');
            $table->date('fecha')->format('Y.m.d');
            $table->foreignId('idCliente')->constrained('tb_clientes');
            $table->string('observacion',255);
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('tb_orden_pedido_cliente');
    }
}
