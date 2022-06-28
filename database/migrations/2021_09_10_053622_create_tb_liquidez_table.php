<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLiquidezTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_liquidez', function (Blueprint $table) {
            $table->id();
            $table->integer('activocorriente')->unsigned();
            $table->integer('pasivocorriente')->unsigned();
            $table->float('razoncorriente');
            $table->integer('capitaldetrabajo')->unsigned();
            $table->integer('inventario')->unsigned();
            $table->float('pruebaacida');
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
        Schema::dropIfExists('tb_liquidez');
    }
}
