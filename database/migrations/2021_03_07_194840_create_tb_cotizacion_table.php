<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCotizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->integer('consecutivo');
            $table->date('fecha')->format('Y.m.d');
            $table->string('condicionEntrega',255);
            $table->integer('vigencia');
            $table->foreignId('idCliente')->constrained('tb_clientes');
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
        Schema::dropIfExists('tb_cotizacion');
    }
}
