<?php
use App\Tb_configuracion_basica;
use Illuminate\Database\Seeder;

class Tb_configuracion_basicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_configuracion_basica.json'));
        foreach ($data as $item){
            Tb_configuracion_basica::create(array(
                //'id' => $item->IdRol,
                'nombre' => $item->nombre,
                'direccion' => $item->direccion,
                'nit' => $item->nit,
                'telefono' => $item->telefono,
                'representante' => $item->representante,
                'cajaCompensacion' => $item->cajaCompensacion,
                'arl' => $item->arl,
                'nivelRiesgo' => $item->nivelRiesgo,
                'idTipoNomina' => $item->idTipoNomina
            ));
            }
    }
}
