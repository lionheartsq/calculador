<?php

use App\Tb_hoja_de_costo;
use Illuminate\Database\Seeder;

class Tb_hoja_de_costoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_hoja_de_costos.json'));
        foreach ($data as $item){
            Tb_hoja_de_costo::create(array(
                //'id' => $item->IdRol,
                'idProducto' => $item->IdProducto,
                'capacidadMensual' => $item->CapacidadMensual,
                'estado' => $item->Estado
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
