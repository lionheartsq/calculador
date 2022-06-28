<?php

use App\Tb_gestion_materia_prima;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tb_gestion_materia_primaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_gestion_materia_prima.json'));
        foreach ($data as $item){
            Tb_gestion_materia_prima::create(array(
                'gestionMateria' => $item->gestionMateria,
                'idUnidadBase' => $item->idUnidadBase,
                'precioBase' => $item->precioBase,
                'idTipoMateria' => $item->idTipoMateria,
                'estado' => $item->estado,
                'idEmpresa' => $item->idEmpresa
            ));
            }
    }
}
