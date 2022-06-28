<?php

namespace App\Http\Controllers;

use App\Tb_materia_prima_producto;
use App\Tb_mano_de_obra_producto;
use App\Tb_gestion_materia_prima;
use App\Tb_proceso;
use App\Tb_simulacion;
use App\tb_hoja_de_costo;
use App\Tb_concepto_cif;
use App\Tb_maquinaria;
use App\Tb_producto;
use App\Tb_rela_simulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\ArrayInput;
use Illuminate\Support\Facades\Auth;

class Hoja_De_CostosController extends Controller
{
        public function acumuladoTotal($identificador)
        {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $total = 0;
            $acumuladomd = 0;
            $acumuladomi = 0;
            $acumuladomo = 0;
            $acumuladocif = 0;
            $acumuladomaquinaria = 0;
            $acumuladocift = 0;
            $acumuladotiempomo = 0;
            $capacidadproducto = 0;
            $acumuladocapacidad = 0;

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
            $total = $acumuladomd + $total;
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
                $total = $acumuladomi + $total;
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
                $total = $acumuladomo + $total;
            }

            //cif
            $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif.valor) IS NULL THEN 0
            ELSE SUM(tb_concepto_cif.valor) END) as acumuladocif");
            $ciftotales = DB::table('tb_concepto_cif')
            ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
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
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
            ->select($query4)
            ->get();
            foreach($totales as $totalg){
                $acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
                $acumuladocift = $acumuladocift + $acumuladomaquinaria;
            }

            //tiempomanodeobra
            $query5 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto.tiempo) IS NULL THEN 0
            ELSE ROUND(SUM(tb_mano_de_obra_producto.tiempo),0) END) as acumuladotiempomo");
            $tiempomanodeobra = DB::table('tb_mano_de_obra_producto')
            ->select($query5)
            ->where('tb_mano_de_obra_producto.idHoja','=',$identificador)
            ->get();
            foreach($tiempomanodeobra as $tmanodeo){
                $acumuladotiempomo = $tmanodeo->acumuladotiempomo + $acumuladotiempomo;
            }

            $productos=Tb_producto::findOrFail($identificador)->select('foto','producto')->get();
            foreach($productos as $producto){
                $foto = $producto->foto;
                $producto = $producto->producto;
            }

            //capacidadproduccion
            $productos=tb_hoja_de_costo::where('id','=',$identificador)->select('capacidadMensual')->first();
            $capacidadmensual = $productos->capacidadMensual;
            $capacidadproducto = $capacidadproducto+$capacidadmensual;

            $capacidades = tb_hoja_de_costo::where('estado','=','1')
            ->select('capacidadMensual')->get();
            foreach($capacidades as $capacidad){
                $acumuladocapacidad = $capacidad->capacidadMensual + $acumuladocapacidad;
            }

            $cifporproducto=intval($acumuladocift/$capacidadproducto);
            $ciftotalaterrizada=intval($acumuladocift/$acumuladocapacidad);
            $total=$total+$cifporproducto;

            $totales = ([
                [
                'acumuladomd'         => $acumuladomd,
                'acumuladomd'         => $acumuladomd,
                'acumuladomi'         => $acumuladomi,
                'acumuladomo'         => $acumuladomo,
                'acumuladocif'        => $acumuladocif,
                'acumuladomaquinaria' => $acumuladomaquinaria,
                'acumuladocift'       => $acumuladocift,
                'acumuladototal'      => $total,
                'capacidadproducto'   => $capacidadproducto,
                'cifporproducto'      => $cifporproducto,
                'ciftotalaterrizada'  => $ciftotalaterrizada,
                'acumuladotiempomo'   => $acumuladotiempomo,
                'foto'                => $foto,
                'producto'            => $producto
                ]
            ]);

            return ['totales' => $totales];

        }
//---------------------------------------------------------------------------------------------------------------------------------//
        public function maquinariaTotal($identificador)
        {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $acumuladomaquinaria = 0;
            $id = 1;

            //maquinaria
            $query = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
            ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
            $totales = DB::table('tb_maquinaria')
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
            ->select($query)
            ->get();
            foreach($totales as $totalg){
                $acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
            }

            $maquinarias = $acumuladomaquinaria;
            return ['maquinarias' => $maquinarias];
        }
//---------------------------------------------------------------------------------------------------------------------------------//
        public function cifTiempos($identificador)
        {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            //el identificador que paso a esta función es el idSimulacion
            $acumuladocif = 0;
            $acumuladomaquinaria = 0;
            $acumuladocift = 0;

            //cif
            $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif.valor) IS NULL THEN 0
            ELSE SUM(tb_concepto_cif.valor) END) as acumuladocif");
            $ciftotales = DB::table('tb_concepto_cif')
            ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
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
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
            ->select($query4)
            ->get();
            foreach($totales as $totalg){
                $acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
                $acumuladocift = $acumuladocift + $acumuladomaquinaria;
            }

            $acumuladoproduccion=0;
            $acumuladotiempo=0;
            $acumuladocalculo=0;

            $querys = DB::raw("SUM(tb_rela_simulacion.unidades) as acumuladoprod,SUM(tb_rela_simulacion.tiempo) as acumuladotiem,
            SUM(tb_rela_simulacion.unidades * tb_rela_simulacion.tiempo) as acumuladocalc");
            $totalprod = DB::table('tb_rela_simulacion')->where('idSimulacion','=',$identificador)
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
            ->where('tb_rela_simulacion.idSimulacion', '=', $identificador)->get();

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
            $acumuladocif = 0;
            $acumuladomaquinaria = 0;
            $acumuladocift = 0;
            $capacidadproducto = 0;

            $productos = Tb_producto::where('id','=',$identificador)
            ->select('producto','referencia','foto')->get();
            foreach($productos as $producto){
                $nombrep = $producto->producto;
                $referenciap = $producto->referencia;
                $fotop = $producto->foto;
                }

            $simulate = Tb_simulacion::where('id','=',$simulacion)
            ->select('descripcion')->get();
            foreach($simulate as $simula){
                $simuladet = $simula->descripcion;
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
            ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
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
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
            ->select($query4)
            ->get();
            foreach($totales as $totalg){
                $acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
                $acumuladocift = $acumuladocift + $acumuladomaquinaria;
            }

            //capacidadproduccion producto
            $unidadprod=0;
            $tiempoprod=0;

            $queryu = DB::raw("tb_rela_simulacion.unidades,tb_rela_simulacion.tiempo");
            $totalprodu = DB::table('tb_rela_simulacion')->where([
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

            $conceptos = Tb_concepto_cif::orderBy('id','desc')
            ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
            ->get();

            //maquinaria
            $acumuladomaquinaria=0;

            $querydep = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
            ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
            $totales = DB::table('tb_maquinaria')
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
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
// Las siguientes dos funciones son para la hoja de costos de unidad
//---------------------------------------------------------------------------------------------------------------------------------//
public function unitarioTotalGen(Request $request)
{
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

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
    ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
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
    ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
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
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

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

    $conceptos = Tb_concepto_cif::orderBy('id','desc')
    ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
    ->get();

    //maquinaria
    $acumuladomaquinaria=0;

    $querydep = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
    ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
    $totales = DB::table('tb_maquinaria')
    ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
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
