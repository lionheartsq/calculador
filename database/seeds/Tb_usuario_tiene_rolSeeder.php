<?php

use App\Tb_usuario_tiene_rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tb_usuario_tiene_rolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '1',
            'idRol' => '1',
            'idEmpresa' =>'1'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '2',
            'idRol' => '1',
            'idEmpresa' =>'1'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '3',
            'idRol' => '1',
            'idEmpresa' =>'1'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '4',
            'idRol' => '1',
            'idEmpresa' =>'1'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '5',
            'idRol' => '1',
            'idEmpresa' =>'2'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '6',
            'idRol' => '2',
            'idEmpresa' =>'1'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '7',
            'idRol' => '2',
            'idEmpresa' =>'2'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '8',
            'idRol' => '1',
            'idEmpresa' =>'1'
        ]);
        DB::table('tb_usuario_tiene_rol')->insert([
            'idUser' => '9',
            'idRol' => '2',
            'idEmpresa' =>'1'
        ]);

    }
}
