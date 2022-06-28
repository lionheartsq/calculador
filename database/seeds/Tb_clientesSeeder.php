<?php

use App\Tb_cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class Tb_clientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tb_clientes')->insert([
            'documento'   => '22222',
            'nombre'      => 'Cliente',
            'apellido'    => 'Indeterminado',
            'direccion'   => 'Indeterminado',
            'telefono'    => '22222',
            'correo'      => 'correo@correo.co',
            'estado'      => '1',
            'idEmpresa' => '1'
        ]);
    }
}
