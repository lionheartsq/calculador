<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbConfiguracionBasicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_configuracion_basica', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',255);
            $table->string('direccion',255);
            $table->string('nit',255);
            $table->bigInteger('telefono');
            $table->string('representante',255);
            $table->string('cajaCompensacion',255);
            $table->string('arl',255);
            $table->integer('nivelRiesgo');
            //$table->float('nivelRiesgo',8,3);
            $table->foreignId('idTipoNomina')->constrained('tb_tipo_nomina');
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
        Schema::dropIfExists('tb_configuracion_basica');
    }
}
