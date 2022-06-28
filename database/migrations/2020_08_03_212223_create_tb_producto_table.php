<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_producto', function (Blueprint $table) {
            $table->id();
            $table->string('producto', 255);
            $table->string('referencia', 255);
            $table->string('foto', 2038);
            $table->string('descripcion', 255);
            $table->foreignId('idColeccion')->constrained('tb_coleccion');
            $table->foreignId('idArea')->constrained('tb_area');
            $table->integer('presentacion');
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('tb_producto');
    }
}
