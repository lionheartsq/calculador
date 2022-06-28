<?php

namespace App\Http\Controllers;

use App\Tb_materia_prima_producto;
use App\Tb_gestion_materia_prima;
use App\Tb_unidad_base;
use App\Tb_kardex_almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_materia_prima_productoController extends Controller
{
    public function index(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        $identificador= $request->identificador;

        if ($buscar=='') {
            $materiaprimaproductos = Tb_materia_prima_producto::join("tb_gestion_materia_prima","tb_materia_prima_producto.idMateriaPrima","=","tb_gestion_materia_prima.id")
            ->leftJoin('tb_unidad_base',function($join){
                $join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
            })
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->select('tb_materia_prima_producto.id', 'tb_gestion_materia_prima.id AS idGestionMateria',
            'tb_gestion_materia_prima.gestionMateria', 'tb_gestion_materia_prima.precioBase',
            'tb_unidad_base.id AS idUnidadBase', 'tb_unidad_base.unidadBase', 'tb_materia_prima_producto.cantidad',
            'tb_materia_prima_producto.precio', 'tb_materia_prima_producto.tipoDeCosto', 'tb_materia_prima_producto.idHoja',
            DB::raw('ROUND((tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) as subtotal'))
            ->where('tb_materia_prima_producto.idHoja', '=', $identificador)
            ->orderBy('tb_gestion_materia_prima.id','desc')->paginate(5);
        }
        else if($criterio=='materiaprima'){
            $materiaprimaproductos = Tb_materia_prima_producto::join("tb_gestion_materia_prima","tb_materia_prima_producto.idMateriaPrima","=","tb_gestion_materia_prima.id")
            ->leftJoin('tb_unidad_base',function($join){
                $join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
            })
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->select('tb_materia_prima_producto.id', 'tb_gestion_materia_prima.id AS idGestionMateria',
            'tb_gestion_materia_prima.gestionMateria', 'tb_gestion_materia_prima.precioBase',
            'tb_unidad_base.id AS idUnidadBase', 'tb_unidad_base.unidadBase', 'tb_materia_prima_producto.cantidad',
            'tb_materia_prima_producto.precio', 'tb_materia_prima_producto.tipoDeCosto', 'tb_materia_prima_producto.idHoja',
            DB::raw('ROUND((tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) as subtotal'))
            ->where([
                ['tb_gestion_materia_prima.gestionMateria', 'like', '%'. $buscar . '%'],
                ['tb_materia_prima_producto.idHoja', '=', $identificador],
                     ])
            ->orderBy('tb_gestion_materia_prima.id','desc')->paginate(5);
        }
        else {
            $materiaprimaproductos = Tb_materia_prima_producto::join("tb_gestion_materia_prima","tb_materia_prima_producto.idMateriaPrima","=","tb_gestion_materia_prima.id")
            ->leftJoin('tb_unidad_base',function($join){
                $join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
            })
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->select('tb_materia_prima_producto.id', 'tb_gestion_materia_prima.id AS idGestionMateria',
            'tb_gestion_materia_prima.gestionMateria', 'tb_gestion_materia_prima.precioBase',
            'tb_unidad_base.id AS idUnidadBase', 'tb_unidad_base.unidadBase', 'tb_materia_prima_producto.cantidad',
            'tb_materia_prima_producto.precio', 'tb_materia_prima_producto.tipoDeCosto', 'tb_materia_prima_producto.idHoja',
            DB::raw('ROUND((tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) as subtotal'))
            ->where([
                ['tb_gestion_materia_prima.'.$criterio, 'like', '%'. $buscar . '%'],
                ['tb_materia_prima_producto.idHoja', '=', $identificador],
                     ])
            ->orderBy('tb_gestion_materia_prima.id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$materiaprimaproductos->total(),
                'current_page'  =>$materiaprimaproductos->currentPage(),
                'per_page'      =>$materiaprimaproductos->perPage(),
                'last_page'     =>$materiaprimaproductos->lastPage(),
                'from'          =>$materiaprimaproductos->firstItem(),
                'to'            =>$materiaprimaproductos->lastItem(),
            ],
                'materiaprimaproductos' => $materiaprimaproductos
        ];

    }

    public function selectMateriaPrimaProducto()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $materiaprimaproductos = Tb_materia_prima_producto::join("tb_gestion_materia_prima","tb_materia_prima_producto.idMateriaPrima","=","tb_gestion_materia_prima.id")
        ->leftJoin('tb_unidad_base',function($join){
            $join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
        })
        ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
        ->select('tb_materia_prima_producto.id', 'tb_gestion_materia_prima.id AS idGestionMateria',
        'tb_gestion_materia_prima.gestionMateria', 'tb_gestion_materia_prima.precioBase',
        'tb_unidad_base.id AS idUnidadBase', 'tb_unidad_base.unidadBase', 'tb_materia_prima_producto.cantidad',
        'tb_materia_prima_producto.precio', 'tb_materia_prima_producto.tipoDeCosto', 'tb_materia_prima_producto.idHoja',
        DB::raw('ROUND((tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) as subtotal'))
        ->orderBy('tb_gestion_materia_prima.id','desc')->get();

        return [
            'pagination' => [
                'total'         =>$materiaprimaproductos->total(),
                'current_page'  =>$materiaprimaproductos->currentPage(),
                'per_page'      =>$materiaprimaproductos->perPage(),
                'last_page'     =>$materiaprimaproductos->lastPage(),
                'from'          =>$materiaprimaproductos->firstItem(),
                'to'            =>$materiaprimaproductos->lastItem(),
            ],
                'materiaprimaproductos' => $materiaprimaproductos
        ];

    }

        public function store(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $tb_materia_prima_producto=new Tb_materia_prima_producto();
            $tb_materia_prima_producto->idMateriaPrima=$request->idMateriaPrima;
            $tb_materia_prima_producto->cantidad=$request->cantidad;
            $tb_materia_prima_producto->precio=$request->precio;
            $tb_materia_prima_producto->tipoDeCosto=$request->tipoDeCosto;
            $tb_materia_prima_producto->idHoja=$request->idHoja;
            $tb_materia_prima_producto->save();
        }

        public function update(Request $request)
        {
            if(!$request->ajax()) return redirect('/');
            $tb_materia_prima_producto=Tb_materia_prima_producto::findOrFail($request->id);
            $tb_materia_prima_producto->cantidad=$request->cantidad;
            $tb_materia_prima_producto->precio=$request->precio;
            $tb_materia_prima_producto->tipoDeCosto=$request->tipoDeCosto;
            $tb_materia_prima_producto->save();
        }

        public function deactivate(Request $request)
        {
            //if(!$request->ajax()) return redirect('/');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $tb_materia_prima_producto=Tb_materia_prima_producto::findOrFail($request->id);
            $tb_materia_prima_producto->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }


        public function selectGestionMateria()
        {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $gestionmaterias = Tb_gestion_materia_prima::join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
            ->select('tb_gestion_materia_prima.id as idGestionMateria','tb_gestion_materia_prima.gestionMateria','tb_gestion_materia_prima.precioBase','tb_gestion_materia_prima.idUnidadBase','tb_unidad_base.unidadBase as unidadBase','tb_gestion_materia_prima.estado')
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->where('tb_gestion_materia_prima.estado','=','1')
            ->orderBy('gestionMateria','asc')
            ->get();

            return ['gestionmaterias' => $gestionmaterias];
        }

        public function selectDatosMateria($id)
        {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $datosmaterias = Tb_gestion_materia_prima::join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
            ->select('tb_gestion_materia_prima.id as idGestion','tb_gestion_materia_prima.gestionMateria as nombreMateria','tb_gestion_materia_prima.precioBase as precioBase','tb_unidad_base.unidadBase as unidadBase')
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->where('tb_gestion_materia_prima.id','=',$id)
            ->get();

            return ['datosmaterias' => $datosmaterias];
        }

        public function valorPrecioBase($id)
        { //DATOS de valor segun orden 2 5 y 6 traigo segun idmateria el valor promedio del kardex
            //if(!$request->ajax()) return redirect('/');
            $valorMaterial = 0;
            $preciomaterial = Tb_kardex_almacen::first()
            ->select('tb_kardex_almacen.id','tb_kardex_almacen.precioSaldos')
            ->where([
                ['tb_kardex_almacen.idGestionMateria', '=', $id],
                ['tb_kardex_almacen.precioSaldos', '>', '0'],
            ]) //aca podria poner en el where que mirara que el valor no es cero y probar edit funciona
            ->orderBy('tb_kardex_almacen.idGestionMateria','desc')
            ->get();

            foreach($preciomaterial as $totalg){
                $id = $totalg->id;
                $valorMaterial = $valorMaterial + $totalg->precioSaldos;
            }

            if($valorMaterial>0){
                $valores = Tb_gestion_materia_prima::join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
                ->select('tb_unidad_base.unidadBase as unidadBase')
                ->where('tb_gestion_materia_prima.id','=',$id)
                ->get();

                foreach($valores as $totalg){
                    $unidad = $totalg->unidadBase;
                }

                $valorPrecioBase = $valorMaterial;
            }
            else{
                $precioB = 0;
                $valores = Tb_gestion_materia_prima::join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
                ->select('tb_gestion_materia_prima.precioBase as precioBase','tb_unidad_base.unidadBase as unidadBase')
                ->where('tb_gestion_materia_prima.id','=',$id)
                ->get();

                foreach($valores as $totalg){
                    $valor = $totalg->precioBase + $precioB;
                    $unidad = $totalg->unidadBase;
                }

                $valorPrecioBase = $valor;
            }

            return [
            'valorPrecioBase' => $valorPrecioBase,
            'unidadBase' => $unidad
            ];

        }

/*
        public function valorPrecioBase($id)
        {
            $precioB = 0;
            $valores = Tb_gestion_materia_prima::join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
            ->select('tb_gestion_materia_prima.precioBase as precioBase','tb_unidad_base.unidadBase as unidadBase')
            ->where('tb_gestion_materia_prima.id','=',$id)
            ->get();

            foreach($valores as $totalg){
                $valor = $totalg->precioBase + $precioB;
                $unidad = $totalg->unidadBase;
            }

            $valorPrecioBase = $valor;
            return ['valorPrecioBase' => $valorPrecioBase,
                    'unidadBase' => $unidad
                    ];
        }
*/

}
