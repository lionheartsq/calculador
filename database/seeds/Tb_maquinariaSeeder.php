<?php

use App\Tb_maquinaria;
use Illuminate\Database\Seeder;

class Tb_maquinariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_maquinarias.json'));
        foreach ($data as $item){
            Tb_maquinaria::create(array(
                //'id' => $item->IdRol,
                'maquinaria' => $item->maquinaria,
                'valor' => $item->valor,
                'tiempoDeVidaUtil' => $item->tiempoDeVidaUtil,
                'depreciacionAnual' => $item->depreciacionAnual,
                'depreciacionMensual' => $item->depreciacionMensual,
                'fecha' => $item->fecha,
                'estado' => $item->estado,
                'idEmpresa' => $item->idEmpresa
            ));
            }
        /*
        DB::table('tb_rol')->insert([
            'rol' => 'SuperAdministrador',
        ]);

        DB::table('tb_rol')->insert([
            'rol' => 'Empresario',
        ]
        );
        */
    }
}
