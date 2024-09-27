<?php

use App\Tb_perfil;
use Illuminate\Database\Seeder;

class Tb_perfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$data = json_decode(file_get_contents(__DIR__ . '/json/tb_perfiles.json'));
        //foreach ($data as $item){
            //Tb_perfil::create(array(
                //'perfil' => $item->perfil,
                //'idProceso' => $item->idProceso,
                //'valorMinuto' => $item->valorMinuto,
            //));
            //}

        DB::table('tb_perfil')->insert([
            [
                'perfil' => 'PerfilEjemplo',
                'idProceso' => 1,
                'valorMinuto' => 5000,
            ],
            [
                'perfil' => 'PerfilEjemplo2',
                'idProceso' => 2,
                'valorMinuto' => 6000,
            ],
            
        ]);   
    }
}
