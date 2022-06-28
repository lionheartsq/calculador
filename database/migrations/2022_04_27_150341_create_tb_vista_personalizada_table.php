<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbVistaPersonalizadaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_vista_personalizada', function (Blueprint $table) {

            $table->id();
            $table->boolean('escritorio')->default(1);
            $table->boolean('documentacion')->default(1);
            $table->boolean('administracion')->default(1);
            $table->boolean('conceptosCif')->default(1);
            $table->boolean('materiales')->default(1);
            $table->boolean('productos')->default(1);
            $table->boolean('produccion')->default(1);
            $table->boolean('kardex')->default(1);
            $table->boolean('manoDeObra')->default(1);
            $table->boolean('personas')->default(1);
            $table->boolean('nomina')->default(1);
            $table->boolean('gestionFinanciera')->default(1);
            $table->foreignId('idUser')->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_vista_personalizada');
    }
}
