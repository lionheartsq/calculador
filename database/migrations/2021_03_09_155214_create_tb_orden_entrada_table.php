<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrdenEntradaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_orden_entrada', function (Blueprint $table) {
            $table->id();
            $table->integer('numeroOrden');
            $table->date('fecha')->format('Y.m.d');
            $table->float('cantidad');
            $table->foreignId('idProduccion')->constrained('tb_orden_produccion');
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
        Schema::dropIfExists('tb_orden_entrada');
    }
}
