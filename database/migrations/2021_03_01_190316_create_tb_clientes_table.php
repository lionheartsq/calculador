<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_clientes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('documento');
            $table->string('nombre',255);
            $table->string('apellido',255);
            $table->string('direccion',255);
            $table->bigInteger('telefono');
            $table->string('correo',255);
            $table->boolean('estado')->default(1);
            $table->foreignId('idEmpresa')->constrained('tb_configuracion_basica');
           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_clientes');
    }
}
