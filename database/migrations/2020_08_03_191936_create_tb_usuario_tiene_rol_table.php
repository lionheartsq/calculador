<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUsuarioTieneRolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_usuario_tiene_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idUser')->constrained('users');
            $table->foreignId('idRol')->constrained('tb_rol');
            $table->foreignId('idEmpresa')->constrained('tb_configuracion_basica');
            //$table->boolean('estado')->default(1);
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
        Schema::dropIfExists('tb_usuario_tiene_rol');
    }
}
