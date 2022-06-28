<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idEmpleado')->constrained('tb_empleado');
            $table->date('fecha')->format('Y.m.d');
            $table->boolean('estado')->default(1);//1 true en proceso, 0 false finalizado
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
        Schema::dropIfExists('tb_inventario');
    }
}
