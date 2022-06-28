<?php

namespace App\Http\Controllers;

use App\Tb_simulacion;
use App\Tb_materia_prima_producto;
use App\Tb_mano_de_obra_producto;
use App\Tb_gestion_materia_prima;
use App\Tb_proceso;
use App\tb_hoja_de_costo;
use App\Tb_concepto_cif;
use App\Tb_maquinaria;
use App\Tb_producto;
use App\Tb_rela_simulacion;
use App\Tb_mano_de_obra_producto_simula;
use App\Tb_materia_prima_producto_simula;
use App\Tb_concepto_cif_simula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\ArrayInput;

class Tb_simulacionController extends Controller
{
    //
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
            $simulaciones = Tb_simulacion::where('tb_simulacion.idEmpresa','=',$idEmpresa)
            ->orderBy('id','desc')->paginate(5);
        }
        else {
            $simulaciones = Tb_simulacion::where('tb_simulacion.idEmpresa','=',$idEmpresa)
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$simulaciones->total(),
                'current_page'  =>$simulaciones->currentPage(),
                'per_page'      =>$simulaciones->perPage(),
                'last_page'     =>$simulaciones->lastPage(),
                'from'          =>$simulaciones->firstItem(),
                'to'            =>$simulaciones->lastItem(),
            ],
                'simulaciones' => $simulaciones
        ];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_simulacion=new Tb_simulacion();
        $tb_simulacion->descripcion=$request->detalle;
        $tb_simulacion->fecha=$request->fecha;
        $tb_simulacion->tipoCif=$request->tipoCif;
        $tb_simulacion->idEmpresa=$idEmpresa;
        $tb_simulacion->save();
    }

    public function update(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $tb_simulacion=Tb_simulacion::findOrFail($request->id);
        $tb_simulacion->estado=1;
        $tb_simulacion->save();
    }

//---------------------Funcion para generar las relaciones en las tablas que quedan con los datos-----------------------------------//

    public function estado(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $idSimulacion= $request->id;
        $acumuladomaquinariasimula=0;
        $tb_simulacion=Tb_simulacion::findOrFail($request->id);
        $tb_simulacion->estado=2;
        $tb_simulacion->save();

        //maquinaria y cif al ser globales los calculo desde aca
        $querymaq = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
        ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
        $totales = DB::table('tb_maquinaria')
        ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
        ->select($querymaq)
        ->get();
        foreach($totales as $totalg){
            $acumuladomaquinariasimula = $totalg->acumuladomaquinaria + $acumuladomaquinariasimula;
        }

        $tb_concepto_cif_simula0=new Tb_concepto_cif_simula();
        $tb_concepto_cif_simula0->concepto='Depreciación';
        $tb_concepto_cif_simula0->valor=$acumuladomaquinariasimula;
        $tb_concepto_cif_simula0->idSimulacion=$idSimulacion;
        $tb_concepto_cif_simula0->save();

        $conceptoscif = DB::table('tb_concepto_cif')->where('tb_concepto_cif.estado', '=', '1')->get();
        foreach ($conceptoscif as $conceptocif) {
            $concepto=$conceptocif->concepto;
            $valor=$conceptocif->valor;
            $tb_concepto_cif_simula=new Tb_concepto_cif_simula();
            $tb_concepto_cif_simula->concepto=$concepto;
            $tb_concepto_cif_simula->valor=$valor;
            $tb_concepto_cif_simula->idSimulacion=$idSimulacion;
            $tb_concepto_cif_simula->save();
        }

        $productos = DB::table('tb_rela_simulacion')->where('tb_rela_simulacion.idSimulacion', '=', $idSimulacion)->get();
        foreach ($productos as $producto) {
            $idProducto=$producto->idProducto;

            $manodeobras = DB::table('tb_mano_de_obra_producto')->where('tb_mano_de_obra_producto.idHoja', '=', $idProducto)->get();
            foreach ($manodeobras as $manodeobra) {
                $idPerfil=$manodeobra->idPerfil;
                $tiempo=$manodeobra->tiempo;
                $precio=$manodeobra->precio;
                $tipoPago=$manodeobra->tipoPago;

                $tb_mano_de_obra_producto_simula=new Tb_mano_de_obra_producto_simula();
                $tb_mano_de_obra_producto_simula->idPerfil=$idPerfil;
                $tb_mano_de_obra_producto_simula->tiempo=$tiempo;
                $tb_mano_de_obra_producto_simula->precio=$precio;
                $tb_mano_de_obra_producto_simula->tipoPago=$tipoPago;
                $tb_mano_de_obra_producto_simula->idProducto=$idProducto;
                $tb_mano_de_obra_producto_simula->idSimulacion=$idSimulacion;
                $tb_mano_de_obra_producto_simula->save();
            }

            $materiasprimas = DB::table('tb_materia_prima_producto')->where('tb_materia_prima_producto.idHoja', '=', $idProducto)->get();
            foreach ($materiasprimas as $materiaprima) {
                $idMateriaPrima=$materiaprima->idMateriaPrima;
                $cantidad=$materiaprima->cantidad;
                $precio=$materiaprima->precio;
                $tipoDeCosto=$materiaprima->tipoDeCosto;

                $tb_materia_prima_producto_simula=new Tb_materia_prima_producto_simula();
                $tb_materia_prima_producto_simula->idMateriaPrima=$idMateriaPrima;
                $tb_materia_prima_producto_simula->cantidad=$cantidad;
                $tb_materia_prima_producto_simula->precio=$precio;
                $tb_materia_prima_producto_simula->tipoDeCosto=$tipoDeCosto;
                $tb_materia_prima_producto_simula->idProducto=$idProducto;
                $tb_materia_prima_producto_simula->idSimulacion=$idSimulacion;
                $tb_materia_prima_producto_simula->save();
            }

        }
    }

//---------------------------------------------------------------------------------------------------------------------------------//
public function cifTiempos($identificador)
{
    //el identificador que paso a esta función es el idSimulacion
    $acumuladocift = 0;

    //cif
    $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif_simula.valor) IS NULL THEN 0
    ELSE SUM(tb_concepto_cif_simula.valor) END) as acumuladocif");
    $ciftotales = DB::table('tb_concepto_cif_simula')
    ->where('tb_concepto_cif_simula.idSimulacion', '=', $identificador)
    ->select($query3)
    ->get();
    foreach($ciftotales as $ciftotal){
        $acumuladocift = $ciftotal->acumuladocif + $acumuladocift;
    }

    $acumuladoproduccion=0;
    $acumuladotiempo=0;
    $acumuladocalculo=0;

    $querys = DB::raw("SUM(tb_rela_simulacion.unidades) as acumuladoprod,SUM(tb_rela_simulacion.tiempo) as acumuladotiem,
    SUM(tb_rela_simulacion.unidades * tb_rela_simulacion.tiempo) as acumuladocalc");
    $totalprod = DB::table('tb_rela_simulacion')
    ->where('idSimulacion','=',$identificador)
    ->select($querys)
    ->get();

    foreach($totalprod as $totalpr){
        $acumuladoproduccion = $totalpr->acumuladoprod + $acumuladoproduccion;
        $acumuladotiempo = $totalpr->acumuladotiem + $acumuladotiempo;
        $acumuladocalculo = $totalpr->acumuladocalc + $acumuladocalculo;
    }

    $produccion = $acumuladoproduccion;
    //$tiempo = round($acumuladotiempo, 2);
    $parcial=($acumuladocift/$acumuladocalculo);
    $valorbase=round($parcial,2);
    /*
    $totalcif=round(($valorbase*1800));//valorbase * horastotales -- 3665576
    $unitariocif=round(($totalcif/1200));//totalcif / unidades -- 3055
    */
    $productos = Tb_rela_simulacion::join('tb_producto','tb_rela_simulacion.idProducto','=','tb_producto.id')
    ->select('tb_rela_simulacion.id','tb_rela_simulacion.unidades','tb_rela_simulacion.tiempo','tb_producto.producto',
    'tb_producto.referencia','tb_producto.descripcion','tb_producto.id as idProducto')
    ->where('tb_rela_simulacion.idSimulacion', '=', $identificador)
    ->get();

    return [
        'productos' => $productos,
        'produccion' => $produccion,
        //'tiempo' => $tiempo,
        'acumuladocift' => $acumuladocift,
        'acumuladocalculo' => $acumuladocalculo,
        'valorbase' => $valorbase
    ];

}
//---------------------------------------------------------------------------------------------------------------------------------//
public function unitarioTotal(Request $request)
{
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

    $identificador= $request->identificador;
    $simulacion= $request->simulacion;

    $total = 0;
    $acumuladomd = 0;
    $acumuladomi = 0;
    $acumuladomo = 0;

    $productos = Tb_producto::where('id','=',$identificador)
    ->select('producto','referencia','foto','presentacion')->get();
    foreach($productos as $producto){
        $nombrep = $producto->producto;
        $referenciap = $producto->referencia;
        $fotop = $producto->foto;
        $presentacion = $producto->presentacion;

        if($presentacion==4){
            $presentacion="unidad";
        }
        elseif($presentacion==5){
            $presentacion="par";
        }

        }

    $simulate = Tb_simulacion::where('tb_simulacion.id','=',$simulacion)
    ->where('tb_simulacion.idEmpresa','=',$idEmpresa)
    ->select('descripcion')
    ->get();
    foreach($simulate as $simula){
        $simuladet = $simula->descripcion;
        }

    //directa
    $query = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto_simula.cantidad*tb_materia_prima_producto_simula.precio) IS NULL THEN 0
    ELSE ROUND(SUM(tb_materia_prima_producto_simula.cantidad*tb_materia_prima_producto_simula.precio),0) END) as acumuladomd");
    $materiadirecta = DB::table('tb_materia_prima_producto_simula')
    ->select($query)
    ->where([
        ['tb_materia_prima_producto_simula.idProducto','=',$identificador],
        ['tb_materia_prima_producto_simula.idSimulacion','=',$simulacion],
        ['tb_materia_prima_producto_simula.tipoDeCosto', '=', 'Directo'],
    ])->get();
    foreach($materiadirecta as $materiad){
    $acumuladomd = $materiad->acumuladomd + $acumuladomd;
    }

    //indirecta
    $query1 = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto_simula.cantidad*tb_materia_prima_producto_simula.precio) IS NULL THEN 0
    ELSE ROUND(SUM(tb_materia_prima_producto_simula.cantidad*tb_materia_prima_producto_simula.precio),0) END) as acumuladomi");
    $materiaindirecta = DB::table('tb_materia_prima_producto_simula')
    ->select($query1)
    ->where([
        ['tb_materia_prima_producto_simula.idProducto','=',$identificador],
        ['tb_materia_prima_producto_simula.idSimulacion','=',$simulacion],
        ['tb_materia_prima_producto_simula.tipoDeCosto', '=', 'Indirecto'],
    ])->get();
    foreach($materiaindirecta as $materiaind){
        $acumuladomi = $materiaind->acumuladomi + $acumuladomi;
    }

    //manodeobra
    $query2 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto_simula.tiempo*tb_mano_de_obra_producto_simula.precio) IS NULL THEN 0
    ELSE ROUND(SUM(tb_mano_de_obra_producto_simula.tiempo*tb_mano_de_obra_producto_simula.precio),0) END) as acumuladomo");
    $manodeobra = DB::table('tb_mano_de_obra_producto_simula')
    ->select($query2)
    ->where([
        ['tb_mano_de_obra_producto_simula.idProducto','=',$identificador],
        ['tb_mano_de_obra_producto_simula.idSimulacion','=',$simulacion],
    ])->get();

    foreach($manodeobra as $manodeo){
        $acumuladomo = $manodeo->acumuladomo + $acumuladomo;
    }

    //cif
    $acumuladocift = 0;

    //cif
    $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif_simula.valor) IS NULL THEN 0
    ELSE SUM(tb_concepto_cif_simula.valor) END) as acumuladocif");
    $ciftotales = DB::table('tb_concepto_cif_simula')
    ->where('tb_concepto_cif_simula.idSimulacion', '=', $simulacion)
    ->select($query3)
    ->get();
    foreach($ciftotales as $ciftotal){
        $acumuladocift = $ciftotal->acumuladocif + $acumuladocift;
    }

    //capacidadproduccion producto
    $unidadprod=0;
    $tiempoprod=0;

    $queryu = DB::raw("tb_rela_simulacion.unidades,tb_rela_simulacion.tiempo");
    $totalprodu = DB::table('tb_rela_simulacion')
    ->where([
        ['idSimulacion','=',$simulacion],
        ['idProducto','=',$identificador],
    ])
    ->select($queryu)
    ->get();

    foreach($totalprodu as $totalpru){
        $unidadprod = $totalpru->unidades + $unidadprod;
        $tiempoprod = $totalpru->tiempo + $tiempoprod;
    }
    $unidadesprod = $unidadprod * $tiempoprod;

    //capacidadproduccion total
    $totalcant=0;
    $totalcantiempo=0;
    $acumuladocalculo=0;

    $querys = DB::raw("SUM(tb_rela_simulacion.unidades * tb_rela_simulacion.tiempo) as acumuladocalc");
    $totalprod = DB::table('tb_rela_simulacion')->where('idSimulacion','=',$simulacion)
    ->select($querys)
    ->get();

    foreach($totalprod as $totalpr){
        $acumuladocalculo = $totalpr->acumuladocalc + $acumuladocalculo;
    }

    //$tiempo = round($acumuladotiempo, 2);
    $parcial=($acumuladocift/$acumuladocalculo);
    $valorbase=round($parcial,2);

    $cifproducto=($valorbase*$unidadesprod);
    $cifunitario=($cifproducto/$unidadprod);
    $cifunitariored=round($cifunitario,0);

    $total = $acumuladomd + $acumuladomi + $acumuladomo + $cifunitariored;
    $acumuladomp= $acumuladomd + $acumuladomi;

    return [
        'acumuladomd'         => $acumuladomd,
        'acumuladomi'         => $acumuladomi,
        'acumuladomp'         => $acumuladomp,
        'acumuladomo'         => $acumuladomo,
        'cifunitario'         => $cifunitariored,
        'capacidadproducto'   => $unidadesprod,
        'acumuladocift'       => $acumuladocift,
        'nombrep'             => $nombrep,
        'referenciap'         => $referenciap,
        'fotop'               => $fotop,
        'presentacion'        => $presentacion,
        'simuladet'           => $simuladet,
        'costopar'            => $total,
        'totalvariable'       => $acumuladocalculo,
        'cifproduccion'       => $cifproducto,
        'estimadoproduccion'  => $unidadprod
    ];

}
//------------------------------------------------------------------------------------------------------//
public function hojaDetalle(Request $request)
{
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

    $identificador= $request->identificador;
    $simulacion= $request->simulacion;

    $materiaprimaproductos = Tb_materia_prima_producto_simula::join("tb_gestion_materia_prima","tb_materia_prima_producto_simula.idMateriaPrima","=","tb_gestion_materia_prima.id")
    ->leftJoin('tb_unidad_base',function($join){
        $join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
    })
    ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
    ->select('tb_gestion_materia_prima.gestionMateria','tb_materia_prima_producto_simula.tipoDeCosto',
    DB::raw('ROUND((tb_materia_prima_producto_simula.cantidad*tb_materia_prima_producto_simula.precio),0) as subtotal'))
    ->where([
        ['tb_materia_prima_producto_simula.idProducto','=',$identificador],
        ['tb_materia_prima_producto_simula.idSimulacion','=',$simulacion],
    ])
    ->orderBy('tb_gestion_materia_prima.id','desc')
    ->get();

    # Modelo::join('tablaqueseune',basicamente un on)
    $manodeobraproductos = Tb_mano_de_obra_producto_simula::join("tb_perfil","tb_mano_de_obra_producto_simula.idPerfil","=","tb_perfil.id")
    ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
    ->leftJoin('tb_area',function($join){
        $join->on('tb_proceso.idArea','=','tb_area.id');
    })
    ->where('tb_area.idEmpresa','=',$idEmpresa)
    ->select('tb_perfil.perfil','tb_proceso.proceso',DB::raw('ROUND((tb_mano_de_obra_producto_simula.tiempo*tb_mano_de_obra_producto_simula.precio),0) as subtotal'))
    ->where([
        ['tb_mano_de_obra_producto_simula.idProducto','=',$identificador],
        ['tb_mano_de_obra_producto_simula.idSimulacion','=',$simulacion],
    ])
    ->orderBy('tb_mano_de_obra_producto_simula.id','desc')
    ->get();

    $conceptos = Tb_concepto_cif_simula::where([
        ['tb_concepto_cif_simula.idSimulacion', '=', $simulacion],
        ['tb_concepto_cif_simula.valor','>','0'],
    ])
    ->orderBy('id','asc')->get();

    return [
        'materiaprimaproductos' => $materiaprimaproductos,
        'manodeobraproductos' => $manodeobraproductos,
        'conceptos' => $conceptos
    ];
}
//------------------------------------------------------------------------------------------------------//
// Las siguientes dos funciones son para la hoja de costos de unidad
//---------------------------------------------------------------------------------------------------------------------------------//
public function unitarioTotalGen(Request $request)
{
$identificador= $request->identificador;

$total = 0;
$acumuladomd = 0;
$acumuladomi = 0;
$acumuladomo = 0;
$acumuladocif = 0;
$acumuladomaquinaria = 0;
$acumuladocift = 0;

# Modelo::join('tablaqueseune',basicamente un on)
$productos = Tb_producto::join('tb_hoja_de_costo','tb_producto.id','=','tb_hoja_de_costo.idProducto')
->select('tb_producto.producto as producto','tb_producto.referencia as referencia','tb_producto.foto as foto','tb_producto.presentacion as presentacion',
'tb_hoja_de_costo.capacidadMensual as capacidadMensual')
->where('tb_producto.id','=',$identificador)
->get();

foreach($productos as $producto){
$nombrep = $producto->producto;
$referenciap = $producto->referencia;
$fotop = $producto->foto;
$presentacion = $producto->presentacion;
$unidadesprod = $producto->capacidadMensual;

if($presentacion==4){
    $presentacion="unidad";
}
elseif($presentacion==5){
    $presentacion="par";
}

}

//directa
$query = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio) IS NULL THEN 0
ELSE ROUND(SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) END) as acumuladomd");
$materiadirecta = DB::table('tb_materia_prima_producto')
->select($query)
->where([
['tb_materia_prima_producto.idHoja','=',$identificador],
['tb_materia_prima_producto.tipoDeCosto', '=', 'Directo'],
])->get();
foreach($materiadirecta as $materiad){
$acumuladomd = $materiad->acumuladomd + $acumuladomd;
}

//indirecta
$query1 = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio) IS NULL THEN 0
ELSE ROUND(SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) END) as acumuladomi");
$materiaindirecta = DB::table('tb_materia_prima_producto')
->select($query1)
->where([
['tb_materia_prima_producto.idHoja','=',$identificador],
['tb_materia_prima_producto.tipoDeCosto', '=', 'Indirecto'],
])->get();
foreach($materiaindirecta as $materiaind){
$acumuladomi = $materiaind->acumuladomi + $acumuladomi;
}

//manodeobra
$query2 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio) IS NULL THEN 0
ELSE ROUND(SUM(tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) END) as acumuladomo");
$manodeobra = DB::table('tb_mano_de_obra_producto')
->select($query2)
->where('tb_mano_de_obra_producto.idHoja','=',$identificador)
->get();
foreach($manodeobra as $manodeo){
$acumuladomo = $manodeo->acumuladomo + $acumuladomo;
}

//cif
$acumuladocif = 0;
$acumuladomaquinaria = 0;
$acumuladocift = 0;

//cif
$query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif.valor) IS NULL THEN 0
ELSE SUM(tb_concepto_cif.valor) END) as acumuladocif");
$ciftotales = DB::table('tb_concepto_cif')
->select($query3)
->get();
foreach($ciftotales as $ciftotal){
$acumuladocif = $ciftotal->acumuladocif + $acumuladocif;
$acumuladocift = $acumuladocift + $acumuladocif;
}

//maquinaria
$query4 = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
$totales = DB::table('tb_maquinaria')
->select($query4)
->get();
foreach($totales as $totalg){
$acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
$acumuladocift = $acumuladocift + $acumuladomaquinaria;
}

//capacidadproduccion total
$cifunitario=($acumuladocift/$unidadesprod);
$cifunitariored=round($cifunitario,0);

$total = $acumuladomd + $acumuladomi + $acumuladomo + $cifunitariored;
$acumuladomp= $acumuladomd + $acumuladomi;

return [
'acumuladomd'         => $acumuladomd,
'acumuladomi'         => $acumuladomi,
'acumuladomp'         => $acumuladomp,
'acumuladomo'         => $acumuladomo,
'cifunitario'         => $cifunitariored,
'capacidadproducto'   => $unidadesprod,
'acumuladocift'       => $acumuladocift,
'nombrep'             => $nombrep,
'referenciap'         => $referenciap,
'fotop'               => $fotop,
'presentacion'        => $presentacion,
'costopar'            => $total
];

}
//------------------------------------------------------------------------------------------------------//
public function hojaDetalleGen(Request $request)
{
$identificador= $request->identificador;

$materiaprimaproductos = Tb_materia_prima_producto::join("tb_gestion_materia_prima","tb_materia_prima_producto.idMateriaPrima","=","tb_gestion_materia_prima.id")
->leftJoin('tb_unidad_base',function($join){
$join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
})
->select('tb_gestion_materia_prima.gestionMateria','tb_materia_prima_producto.tipoDeCosto',
DB::raw('ROUND((tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) as subtotal'))
->where('tb_materia_prima_producto.idHoja', '=', $identificador)
->orderBy('tb_gestion_materia_prima.id','desc')
->get();

# Modelo::join('tablaqueseune',basicamente un on)
$manodeobraproductos = Tb_mano_de_obra_producto::join("tb_perfil","tb_mano_de_obra_producto.idPerfil","=","tb_perfil.id")
->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
->leftJoin('tb_area',function($join){
$join->on('tb_proceso.idArea','=','tb_area.id');
})
->select('tb_perfil.perfil','tb_proceso.proceso',DB::raw('ROUND((tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) as subtotal'))
->where('tb_mano_de_obra_producto.idHoja', '=', $identificador)
->orderBy('tb_mano_de_obra_producto.id','desc')
->get();

$conceptos = Tb_concepto_cif::orderBy('id','desc')->get();

//maquinaria
$acumuladomaquinaria=0;

$querydep = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
$totales = DB::table('tb_maquinaria')
->select($querydep)
->get();
foreach($totales as $totalg){
$acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
}

return [
'materiaprimaproductos' => $materiaprimaproductos,
'manodeobraproductos' => $manodeobraproductos,
'conceptos' => $conceptos,
'acumuladomaquinaria' => $acumuladomaquinaria
];
}
//------------------------------------------------------------------------------------------------------//

}
