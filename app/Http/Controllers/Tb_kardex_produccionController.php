<?php

namespace App\Http\Controllers;
use App\Tb_kardex_produccion;
use App\Tb_gestion_materia_prima;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tb_kardex_produccionController extends Controller
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

        if ($buscar=='') {
            # Modelo::join('tablaqueseune',basicamente un on)
            $productos = Tb_kardex_produccion::join('tb_gestion_materia_prima','tb_kardex_produccion.idGestionMateria','=','tb_gestion_materia_prima.id')
            ->where('tb_kardex_produccion.idEmpresa','=',$idEmpresa)
            ->select('tb_kardex_produccion.id','tb_kardex_produccion.fecha','tb_kardex_produccion.detalle','tb_kardex_produccion.cantidad',
            'tb_kardex_produccion.precio','tb_kardex_produccion.cantidadSaldos','tb_kardex_produccion.precioSaldos','tb_kardex_produccion.idGestionMateria',
            'tb_kardex_produccion.tipologia','tb_gestion_materia_prima.gestionMateria as producto','tb_gestion_materia_prima.idUnidadBase',
            'tb_gestion_materia_prima.estado',DB::raw('tb_kardex_produccion.cantidadSaldos * tb_kardex_produccion.precioSaldos as saldos'))
            ->orderBy('tb_kardex_produccion.idGestionMateria','asc')
            ->whereIn('tb_kardex_produccion.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_produccion')->groupBy('idGestionMateria');})
            ->paginate(5);
        }
        else {
            $productos = Tb_kardex_produccion::join('tb_gestion_materia_prima','tb_kardex_produccion.idGestionMateria','=','tb_gestion_materia_prima.id')
            ->where('tb_kardex_produccion.idEmpresa','=',$idEmpresa)
            ->select('tb_kardex_produccion.id','tb_kardex_produccion.fecha','tb_kardex_produccion.detalle','tb_kardex_produccion.cantidad',
            'tb_kardex_produccion.precio','tb_kardex_produccion.cantidadSaldos','tb_kardex_produccion.precioSaldos','tb_kardex_produccion.idGestionMateria',
            'tb_kardex_produccion.tipologia','tb_gestion_materia_prima.gestionMateria as producto','tb_gestion_materia_prima.idUnidadBase',
            'tb_gestion_materia_prima.estado',DB::raw('tb_kardex_produccion.cantidadSaldos * tb_kardex_produccion.precioSaldos as saldos'))
            ->whereIn('tb_kardex_produccion.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_produccion')->groupBy('idGestionMateria');})
            ->where('tb_kardex_produccion.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_kardex_produccion.idGestionMateria','asc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         =>$productos->total(),
                'current_page'  =>$productos->currentPage(),
                'per_page'      =>$productos->perPage(),
                'last_page'     =>$productos->lastPage(),
                'from'          =>$productos->firstItem(),
                'to'            =>$productos->lastItem(),
            ],
            'productos' =>  $productos
        ];

    }

    public function listar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->identificador;

        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $productos = Tb_kardex_produccion::join('tb_gestion_materia_prima','tb_kardex_produccion.idGestionMateria','=','tb_gestion_materia_prima.id')
            ->where('tb_kardex_produccion.idEmpresa','=',$idEmpresa)
            ->select('tb_kardex_produccion.id as idMateria','tb_kardex_produccion.fecha','tb_kardex_produccion.detalle','tb_kardex_produccion.cantidad',
            'tb_kardex_produccion.precio',DB::raw('tb_kardex_produccion.cantidad * tb_kardex_produccion.precio as preciototal'),
            'tb_kardex_produccion.cantidadSaldos','tb_kardex_produccion.precioSaldos','tb_kardex_produccion.idGestionMateria',
            'tb_kardex_produccion.tipologia','tb_gestion_materia_prima.gestionMateria as producto','tb_gestion_materia_prima.idUnidadBase',
            'tb_gestion_materia_prima.estado',DB::raw('tb_kardex_produccion.cantidadSaldos * tb_kardex_produccion.precioSaldos as totalsaldos'))
            ->where('tb_kardex_produccion.idGestionMateria', '=', $identificador)
            ->orderBy('tb_kardex_produccion.id','asc')->paginate(5);

            return [
                'pagination' => [
                    'total'         =>$productos->total(),
                    'current_page'  =>$productos->currentPage(),
                    'per_page'      =>$productos->perPage(),
                    'last_page'     =>$productos->lastPage(),
                    'from'          =>$productos->firstItem(),
                    'to'            =>$productos->lastItem(),
                ],
                'productos' =>  $productos
            ];

    }
    public function general()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $materias = Tb_gestion_materia_prima::where('estado','=','1')
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->select('tb_gestion_materia_prima.id as idMateria','tb_gestion_materia_prima.gestionMateria as materia')
            ->get();

        return ['materias' =>  $materias];

    }
    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');

        $fecha=$request->fecha;
        $detalle=$request->detalle;
        $cantidad=$request->cantidad;
        $precio=$request->precio;
        $idProducto=$request->idProducto;
        $tipologia=$request->tipologia;
        $valorE=($precio*$cantidad);

        //capturo cantidad precio y producto; internamente busco el registro mas reciente de dicho producto y tomo el valor cantidad de ese registro,
        //ahora, miro los datos del ingreso nuevo, si es un 1 es un ingreso y sumo la nueva cantidad a la de cantidadSaldos del registro anterior,
        //si es 2 es una salida y lo que hago es restar la nueva cantidad de la de cantidadSaldos del registro anterior; el resultado va en el
        //registro nuevo en el campo cantidadSaldos; ahora para llenar el campo de precioSaldos (que es el precio promedio) lo que hago es del registro
        //anterior multiplico cantidadSaldos*precioSaldos y a ese valor le sumo o resto segun el caso el valor del registro nuevo que resulta de
        //cantidad*precio; el resultado lo divido entre el valor que calcule de cantidadSaldos y lo ingreso en precioSaldos; hay que tener muy en
        //cuenta que en el primer registro las cantidad y cantidadSaldos asi como las precio y precioSaldos son identicas.

        $precioSaldos = DB::table('tb_kardex_produccion')
        ->select('id','cantidad as cantidadA','cantidadSaldos as cantidadS','precioSaldos as precioS')
        ->where('tb_kardex_produccion.idGestionMateria','=', $idProducto)
        ->orderByDesc('id')
        ->limit(1)
        ->get();

        $precioSaldoscant= DB::table('tb_kardex_produccion')
        ->where('tb_kardex_produccion.idGestionMateria','=', $idProducto)
        ->count();

        if (!$precioSaldoscant) {
            $suma1=$cantidad;
            $suma2=$precio;
        }
        else{
            foreach($precioSaldos as $precioS){
                $cantidadA = $precioS->cantidadA;
                $cantidadS = $precioS->cantidadS;
                $precioSA = $precioS->precioS;
                $valorP=($cantidadS*$precioSA);
            }
            if($tipologia == 1){
            $suma1=$cantidadS+$cantidad;
            $suma0=$valorP+$valorE;
            $suma2=($suma0/$suma1);
            }
            else{
                $suma1=$cantidadS-$cantidad;
                $suma0=$valorP-$valorE;
                $suma2=($suma0/$suma1);
            }
        }

        $tb_kardex_produccion=new Tb_kardex_produccion();
        $tb_kardex_produccion->detalle=$detalle;
        $tb_kardex_produccion->cantidad=$cantidad;
        $tb_kardex_produccion->precio=$precio;
        $tb_kardex_produccion->idGestionMateria=$idProducto;
        $tb_kardex_produccion->tipologia=$tipologia;
        $tb_kardex_produccion->fecha=$fecha;
        $tb_kardex_produccion->cantidadSaldos=$suma1;
        $tb_kardex_produccion->precioSaldos=$suma2;
        $tb_kardex_produccion->idEmpresa=$idEmpresa;
        $tb_kardex_produccion->save();
    }
}
