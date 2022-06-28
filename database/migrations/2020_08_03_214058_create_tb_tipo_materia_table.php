<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTipoMateriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_tipo_materia', function (Blueprint $table) {
            $table->id();
            $table->string('tipoMateria', 255);
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
        Schema::dropIfExists('tb_tipo_materia');
    }
}
