<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Tb_nomina;

class Tb_nominaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_nomina')->insert([
            'fechaInicio' => '1900-01-01',
            'fechaFin' => '2100-01-01',
            'tipo' => '1',
            'observacion' => 'Nómina de prueba',
            'estado' => '1',
            'idEmpresa' => '1'
        ]);
    }
}
