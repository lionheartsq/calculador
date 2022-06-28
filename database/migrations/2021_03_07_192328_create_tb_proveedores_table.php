<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_proveedores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nit');
            $table->string('razonSocial',255);
            $table->string('contacto',255);
            $table->bigInteger('telefono');
            $table->string('direccion',255);
            $table->string('correo',255);
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
        Schema::dropIfExists('tb_proveedores');
    }
}
