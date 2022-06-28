<?php

use Illuminate\Database\Seeder;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Paula',
            'email' => 'pacolmenares02@misena.edu.co',
            'password' => bcrypt('Paula'),
        ]);

        DB::table('users')->insert([
            'name' => 'Freddy',
            'email' => 'freddy.mendez@misena.edu.co',
            'password' => bcrypt('Instructor'),
        ]);

        DB::table('users')->insert([
            'name' => 'Luis',
            'email' => 'ljgarcia899@misena.edu.co',
            'password' => bcrypt('Luis'),
        ]);

        DB::table('users')->insert([
            'name' => 'Luz',
            'email' => 'luzariasarias@misena.edu.co',
            'password' => bcrypt('Instructora'),
        ]);

        DB::table('users')->insert([
            'name' => 'Empresario',
            'email' => 'empresario@gmail.com',
            'password' => bcrypt('Empresario'),
        ]);

        DB::table('users')->insert([
            'name' => 'Luis',
            'email' => 'lusuario@misena.edu.co',
            'password' => bcrypt('Luis'),
        ]);

        DB::table('users')->insert([
            'name' => 'Empresario',
            'email' => 'usuario@gmail.com',
            'password' => bcrypt('Empresario'),
        ]);

        DB::table('users')->insert([
            'name' => 'Alex E',
            'email' => 'ealexv@misena.edu.co',
            'password' => bcrypt('Alex'),
        ]);

        DB::table('users')->insert([
            'name' => 'Alex U',
            'email' => 'uealexv@misena.edu.co',
            'password' => bcrypt('Alex'),
        ]);

    }
}
