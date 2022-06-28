<?php

use App\Tb_producto;
use Illuminate\Database\Seeder;

class Tb_productoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_productos.json'));
        foreach ($data as $item){
            Tb_producto::create(array(
                //'id' => $item->IdRol,
                'producto' => $item->Producto,
                'referencia' => $item->Referencia,
                'foto' => $item->Foto,
                'descripcion' => $item->Descripcion,
                'idColeccion' => $item->IdColeccion,
                'idArea' => $item->IdArea,
                'estado' => $item->Estado,
                'presentacion' => $item->Presentacion
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
