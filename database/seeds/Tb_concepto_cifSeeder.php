<?php

use App\Tb_concepto_cif;
use Illuminate\Database\Seeder;

class Tb_concepto_cifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_concepto_cif.json'));
        foreach ($data as $item){
            Tb_concepto_cif::create(array(
                //'id' => $item->IdRol,
                'concepto' => $item->concepto,
                'valor' => $item->valor,
                'estado' => $item->estado,
                'idEmpresa' => $item->idEmpresa
            ));
            }
    }
}
