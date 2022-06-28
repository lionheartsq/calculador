<?php

use App\Tb_coleccion;
use Illuminate\Database\Seeder;

class Tb_coleccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_colecciones.json'));
        foreach ($data as $item){
            Tb_coleccion::create(array(
                //'id' => $item->IdRol,
                'coleccion' => $item->coleccion,
                'referencia' => $item->referencia,
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
