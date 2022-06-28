<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Tb_empleado;

class Tb_empleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_empleado.json'));
        foreach ($data as $item){
            Tb_empleado::create(array(
                'documento' => $item->documento,
                'idPerfil' => $item->idPerfil,
                'nombre' => $item->nombre,
                'apellido' => $item->apellido,
                'direccion' => $item->direccion,
                'genero' => $item->genero,
                'telefono' => $item->telefono,
                'correo' => $item->correo,
                'contacto' => $item->contacto,
                'telefonocontacto' => $item->telefonocontacto,
                'idEps' => $item->idEps,
                'idPensiones' => $item->idPensiones,
                'tipoSangre'=>$item->tipoSangre,
                'enfermedades'=>$item->enfermedades,
                'idEmpresa' => $item->idEmpresa
            ));
            }
    }
}
