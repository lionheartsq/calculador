<?php

use App\Tb_unidad_base;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tb_unidad_baseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_unidad_bases.json'));
        foreach ($data as $item){
            Tb_unidad_base::create(array(
                //'id' => $item->id,
                'unidadBase' => $item->unidadBase,
            ));
            }
        */
        DB::table('tb_unidad_base')->insert([
            'unidadBase' => 'dm2',
            'idEmpresa' => '1'
        ]);

        DB::table('tb_unidad_base')->insert([
            'unidadBase' => 'metro',
            'idEmpresa' => '1'
        ]);

        DB::table('tb_unidad_base')->insert([
            'unidadBase' => 'Hoja',
            'idEmpresa' => '1'
        ]);

        DB::table('tb_unidad_base')->insert([
            'unidadBase' => 'Unidad',
            'idEmpresa' => '1'
        ]);

        DB::table('tb_unidad_base')->insert([
            'unidadBase' => 'Par',
            'idEmpresa' => '1'
        ]);

        DB::table('tb_unidad_base')->insert([
            'unidadBase' => 'ml',
            'idEmpresa' => '1'
        ]);
    }
}
