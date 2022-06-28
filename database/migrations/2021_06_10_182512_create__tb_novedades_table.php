<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_novedades', function (Blueprint $table) {
            $table->id();
            $table->date('fechaNovedad');
            $table->string('concepto', 255);
            $table->float('cantidad',10,2);
            $table->float('unitario',10,2);
            $table->float('valor',10,2);
            $table->string('observacion', 255);
            $table->boolean('tipologia')->default(1);
            $table->foreignId('idEmpleado')->constrained('tb_empleado');
            $table->foreignId('idNomina')->constrained('tb_nomina');
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
        Schema::dropIfExists('tb_novedades');
    }
}
