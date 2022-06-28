<?php

use App\Tb_proveedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class Tb_proveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tb_proveedores')->insert([
            'nit'         => '22222',
            'razonSocial' => 'Indeterminado',
            'contacto'    => 'Indeterminado',
            'telefono'    => '22222',
            'direccion'   => 'Indeterminado',
            'correo'      => 'correo@correo.co',
            'estado'      => '1',
            'idEmpresa' => '1'
        ]);
    }
}
