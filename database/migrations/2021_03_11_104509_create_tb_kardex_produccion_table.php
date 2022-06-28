<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbKardexProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_kardex_produccion', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('detalle', 255);
            $table->float('cantidad');
            $table->integer('precio');
            $table->float('cantidadSaldos');
            $table->integer('precioSaldos');
            $table->foreignId('idGestionMateria')->constrained('tb_gestion_materia_prima');
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
        Schema::dropIfExists('tb_kardex_produccion');
    }
}
