<?php

use App\Tb_simulacion;
use Illuminate\Database\Seeder;

class Tb_simulacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_simulacion.json'));
        foreach ($data as $item){
            Tb_simulacion::create(array(
                //'id' => $item->IdRol,
                'descripcion' => $item->descripcion,
                'fecha' => $item->fecha,
                'tipoCif' => $item->tipoCif,
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
