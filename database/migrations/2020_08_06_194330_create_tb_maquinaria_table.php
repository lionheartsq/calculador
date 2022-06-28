<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMaquinariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_maquinaria', function (Blueprint $table) {
            $table->id();
            $table->string('maquinaria', 255);
            $table->integer('valor');
            $table->integer('tiempoDeVidaUtil');
            $table->integer('depreciacionAnual');
            $table->integer('depreciacionMensual');
            $table->date('fecha');
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
        Schema::dropIfExists('tb_maquinaria');
    }
}
