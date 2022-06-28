<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbGestionMateriaPrimaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_gestion_materia_prima', function (Blueprint $table) {
            $table->id();
            $table->string('gestionMateria', 255);
            $table->foreignId('idUnidadBase')->constrained('tb_unidad_base');
            $table->integer('precioBase')->unsigned();
            $table->foreignId('idTipoMateria')->constrained('tb_tipo_materia');
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
        Schema::dropIfExists('tb_gestion_materia_prima');
    }
}
