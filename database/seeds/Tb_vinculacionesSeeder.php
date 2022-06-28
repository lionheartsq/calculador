<?php

use App\Tb_vinculaciones;
use Illuminate\Database\Seeder;

class Tb_vinculacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_vinculaciones.json'));
        foreach ($data as $item){
            Tb_vinculaciones::create(array(
                'tipoContrato' => $item->tipoContrato,
                'tipoSalario' => $item->tipoSalario,
                'salarioBasicoMensual' => $item->salarioBasicoMensual,
                'fechaInicio' => $item->fechaInicio,
                'tiempoContrato' => $item->tiempoContrato,
                'fechaFin' => $item->fechaFin,
                'idEmpleado' => $item->idEmpleado,
                'idNivelArl' => $item->idNivelArl,
                'estado'=>$item->estado,
                'idEmpresa' => $item->idEmpresa
            ));
            }
    }
}
