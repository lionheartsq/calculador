<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFactoresNominaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_factores_nomina', function (Blueprint $table) {
            $table->id();
            $table->float('extraDiurna',8,2);
            $table->float('extraNocturna',8,2);
            $table->float('horaDominical',8,2);
            $table->float('festivaDiurna',8,2);
            $table->float('festivaNocturna',8,2);
            $table->float('recargos',8,2);
            $table->float('minimolegal',10,2);
            $table->float('auxilio',10,2);
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
        Schema::dropIfExists('tb_factores_nomina');
    }
}
