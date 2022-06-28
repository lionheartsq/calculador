<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_eps'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_epsSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_administradora_pensiones'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_administradora_pensionesSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_niveles_riesgo'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_niveles_riesgoSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_arl'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_arlSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_caja_compensacion'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_caja_compensacionSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_tipo_nomina'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_tipo_nominaSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_configuracion_basica'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_configuracion_basicaSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_proveedores'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_proveedoresSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_clientes'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_clientesSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_documentos'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_documentosSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_area'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_areaSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_proceso'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_procesoSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_perfil'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_perfilSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_informacion_financiera'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_informacion_financieraSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_rol'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_rolSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'users'
        ]);

        //funcion principal que llama cada seeder
        $this->call(UserSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_usuario_tiene_rol'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_usuario_tiene_rolSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_unidad_base'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_unidad_baseSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_tipo_materia'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_tipo_materiaSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_empleado'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_empleadoSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_riesgo_adicional'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_riesgo_adicionalSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_factores_nomina'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_factores_nominaSeeder::class);
//-------------------------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_nomina'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_nominaSeeder::class);
//-------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------//

//-----------------------------Comento seeders de tiempos desde aca--------------------------------------//

        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_esfuerzo_mental'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_esfuerzo_mentalSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_esfuerzo_fisico'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_esfuerzo_fisicoSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_suplementarios'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_suplementariosSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_necesidades_personales'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_necesidades_personalesSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_calificacion_habilidades'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_calificacion_habilidadesSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
         $this->truncateTables([
            'tb_calificacion_esfuerzo'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_calificacion_esfuerzoSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_calificacion_condiciones'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_calificacion_condicionesSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_calificacion_consistencia'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_calificacion_consistenciaSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_monotonia'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_monotoniaSeeder::class);
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_espera'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_esperaSeeder::class);
//-------------------------------------------------------------------//

//-------------------------------------------Hasta acá---------------------------------------------------//
//-------------------------------------------------------------------//
        //primero vacia la tabla y luego la llena ojo
        $this->truncateTables([
            'tb_vinculaciones'
        ]);

        //funcion principal que llama cada seeder
        $this->call(Tb_vinculacionesSeeder::class);
//-------------------------------------------------------------------//

//--Seeders de base de muestra, se puede comentar si se requiere la base con lo inicial solamente--//
        $this->truncateTables([
            'tb_gestion_materia_prima'
        ]);
        $this->call(Tb_gestion_materia_primaSeeder::class);

        $this->truncateTables([
            'tb_concepto_cif'
        ]);
        $this->call(Tb_concepto_cifSeeder::class);

        $this->truncateTables([
            'tb_maquinaria'
        ]);
        $this->call(Tb_maquinariaSeeder::class);

        $this->truncateTables([
            'tb_coleccion'
        ]);
        $this->call(Tb_coleccionSeeder::class);

        $this->truncateTables([
            'tb_producto'
        ]);
        $this->call(Tb_productoSeeder::class);

        $this->truncateTables([
            'tb_hoja_de_costo'
        ]);
        $this->call(Tb_hoja_de_costoSeeder::class);

        $this->truncateTables([
            'tb_mano_de_obra_producto'
        ]);
        $this->call(Tb_mano_de_obra_productoSeeder::class);

        $this->truncateTables([
            'tb_materia_prima_producto'
        ]);
        $this->call(Tb_materia_prima_productoSeeder::class);

        $this->truncateTables([
            'tb_vista_personalizada'
        ]);
        $this->call(Tb_vista_personalizadaSeeder::class);
        /*
        $this->truncateTables([
            'tb_simulacion'
        ]);
        $this->call(Tb_simulacionSeeder::class);

        $this->truncateTables([
            'tb_rela_simulacion'
        ]);
        $this->call(Tb_rela_simulacionSeeder::class);
        */
//--Hasta aca se comentan los seeders de base de muestra para iniciar con lo básico--//

//----------------------------------------------------------------------------------------------//

//--Tener cuidado con este cierre--//
    }
//--Tener cuidado con este cierre--//

    //funcion que deshabilita revision de claves foraneas para borrar tablas y luego la habilita nuevamente
    protected function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
