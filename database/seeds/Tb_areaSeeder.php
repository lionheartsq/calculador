<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Tb_area;

class Tb_areaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(file_get_contents(__DIR__ . '/json/tb_areas.json'));
        foreach ($data as $item){
            Tb_area::create(array(
                //'id' => $item->IdArea,
                'area' => $item->Area,
                'idEmpresa' => $item->idEmpresa
            ));
            }
        /*
        DB::table('tb_area')->insert([
            'area' => 'Calzado',
        ]);

        DB::table('tb_area')->insert([
            'area' => 'Marroquineria',
        ]
        );
        */
    }
}
