<?php

use App\Tb_proceso;
use Illuminate\Database\Seeder;

class Tb_procesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$data = json_decode(file_get_contents(__DIR__ . '/json/tb_procesos.json'));
        //foreach ($data as $item){
            //Tb_proceso::create(array(
                //'proceso' => $item->proceso,
                //'idArea' => $item->idArea,
                //'idEmpresa' => $item->idEmpresa,
            //));
            //}

            DB::table('tb_proceso')->insert([
                [
                    'proceso' => 'ProcesoEjemplo',
                    'idArea' => 1,
                    'idEmpresa' => 1,
                ],
                [
                    'proceso' => 'ProcesoEjemplo2',
                    'idArea' => 1,
                    'idEmpresa' => 1,
                ],
            ]);    
    }
}
