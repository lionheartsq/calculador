<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRotacioncarteraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rotacioncartera', function (Blueprint $table) {
            $table->id();
            $table->date('fechainicial');
            $table->date('fechafinal');
            $table->integer('tipoperiodo')->unsigned();
            $table->integer('saldoperiodoactual')->unsigned();
            $table->integer('saldoperiodoanterior')->unsigned();
            $table->integer('costodeventas')->unsigned();
            $table->integer('sumasaldos')->unsigned();
            $table->float('promediosaldos');
            $table->float('rotacioncartera');
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
        Schema::dropIfExists('tb_rotacioncartera');
    }
}
