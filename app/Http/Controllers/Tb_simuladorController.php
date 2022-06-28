<?php

namespace App\Http\Controllers;

use App\Tb_simulador;
use App\Tb_materia_prima_producto;
use App\Tb_mano_de_obra_producto;
use App\Tb_concepto_cif;
use App\Tb_precios_venta;
use App\Tb_producto;
use App\Tb_rela_simulador;
use App\Tb_detallado_simulador;
use App\Tb_mano_de_obra_producto_simulador;
use App\Tb_materia_prima_producto_simulador;
use App\Tb_concepto_cif_simulador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\ArrayInput;

class Tb_simuladorController extends Controller
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
            $simulaciones = Tb_simulador::where('tb_simulador.idEmpresa','=',$idEmpresa)
            ->orderBy('id','desc')->paginate(5);
        }
        else {
            $simulaciones = Tb_simulador::where('tb_simulador.idEmpresa','=',$idEmpresa)
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
        $Tb_simulador=new Tb_simulador();
        $Tb_simulador->descripcion=$request->detalle;
        $Tb_simulador->gastosfijos=$request->gastosfijos;
        $Tb_simulador->fecha=$request->fecha;
        $Tb_simulador->idEmpresa=$idEmpresa;
        $Tb_simulador->save();
    }

    public function update(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $Tb_simulador=Tb_simulador::findOrFail($request->id);
        $Tb_simulador->estado=1;
        $Tb_simulador->save();
    }

//---------------------Funcion para generar las relaciones en las tablas que quedan con los datos-----------------------------------//
//---------------------Esta es la que voy a usar para almacenar lo que hago al calcular punto equ-----------------------------------//

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
        $Tb_simulador=Tb_simulador::findOrFail($request->id);
        $Tb_simulador->estado=2;
        $Tb_simulador->save();

        //maquinaria y cif al ser globales los calculo desde aca
        $querymaq = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
        ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
        $totales = DB::table('tb_maquinaria')
        ->select($querymaq)
        ->get();
        foreach($totales as $totalg){
            $acumuladomaquinariasimula = $totalg->acumuladomaquinaria + $acumuladomaquinariasimula;
        }

        $tb_concepto_cif_simulador0=new Tb_concepto_cif_simulador();
        $tb_concepto_cif_simulador0->concepto='Depreciación';
        $tb_concepto_cif_simulador0->valor=$acumuladomaquinariasimula;
        $tb_concepto_cif_simulador0->idSimulacion=$idSimulacion;
        $tb_concepto_cif_simulador0->save();

        $conceptoscif = DB::table('tb_concepto_cif')
        ->where('tb_concepto_cif.idEmpresa','=',$idEmpresa)
        ->where('tb_concepto_cif.estado', '=', '1')->get();
        foreach ($conceptoscif as $conceptocif) {
            $concepto=$conceptocif->concepto;
            $valor=$conceptocif->valor;
            $tb_concepto_cif_simulador=new Tb_concepto_cif_simulador();
            $tb_concepto_cif_simulador->concepto=$concepto;
            $tb_concepto_cif_simulador->valor=$valor;
            $tb_concepto_cif_simulador->idSimulacion=$idSimulacion;
            $tb_concepto_cif_simulador->save();
        }

        $productos = DB::table('tb_rela_simulador')->where('tb_rela_simulador.idSimulador', '=', $idSimulacion)->get();
        foreach ($productos as $producto) {
            $idProducto=$producto->idProducto;

            $manodeobras = DB::table('tb_mano_de_obra_producto')->where('tb_mano_de_obra_producto.idHoja', '=', $idProducto)->get();
            foreach ($manodeobras as $manodeobra) {
                $idPerfil=$manodeobra->idPerfil;
                $tiempo=$manodeobra->tiempo;
                $precio=$manodeobra->precio;
                $tipoPago=$manodeobra->tipoPago;

                $Tb_mano_de_obra_producto_simulador=new Tb_mano_de_obra_producto_simulador();
                $Tb_mano_de_obra_producto_simulador->idPerfil=$idPerfil;
                $Tb_mano_de_obra_producto_simulador->tiempo=$tiempo;
                $Tb_mano_de_obra_producto_simulador->precio=$precio;
                $Tb_mano_de_obra_producto_simulador->tipoPago=$tipoPago;
                $Tb_mano_de_obra_producto_simulador->idProducto=$idProducto;
                $Tb_mano_de_obra_producto_simulador->idSimulacion=$idSimulacion;
                $Tb_mano_de_obra_producto_simulador->save();
            }

            $materiasprimas = DB::table('tb_materia_prima_producto')->where('tb_materia_prima_producto.idHoja', '=', $idProducto)->get();
            foreach ($materiasprimas as $materiaprima) {
                $idMateriaPrima=$materiaprima->idMateriaPrima;
                $cantidad=$materiaprima->cantidad;
                $precio=$materiaprima->precio;
                $tipoDeCosto=$materiaprima->tipoDeCosto;

                $tb_materia_prima_producto_simulador=new Tb_materia_prima_producto_simulador();
                $tb_materia_prima_producto_simulador->idMateriaPrima=$idMateriaPrima;
                $tb_materia_prima_producto_simulador->cantidad=$cantidad;
                $tb_materia_prima_producto_simulador->precio=$precio;
                $tb_materia_prima_producto_simulador->tipoDeCosto=$tipoDeCosto;
                $tb_materia_prima_producto_simulador->idProducto=$idProducto;
                $tb_materia_prima_producto_simulador->idSimulacion=$idSimulacion;
                $tb_materia_prima_producto_simulador->save();
            }
        }
/* Hasta aca estoy alimentando las tablas de respaldo, de aca para abajo voy a sacar los datos */
//grupo de consultas para sacar datos globales: costosfijos, gastosfijos, sumapromedioponderado, puntoequilibriototal
        //cif global
            $acumuladocifglobal = 0;

            $ciftotales = DB::table('tb_concepto_cif_simulador')->where('tb_concepto_cif_simulador.idSimulacion', '=', $idSimulacion)->get();
            foreach($ciftotales as $ciftotal){
                $acumuladocif=$ciftotal->valor;
                $acumuladocifglobal = $acumuladocifglobal + $acumuladocif;
            }
        //gastos fijos global
            $gastosfijos=0;

            $simuladorgastosfijos = Tb_simulador::where('id','=',$idSimulacion)
            ->select('gastosfijos')->get();
            foreach($simuladorgastosfijos as $simuladorgastos){
                $simuladorgastosf = $simuladorgastos->gastosfijos;

                $gastosfijos=$gastosfijos+$simuladorgastosf;
            }

            //unidades global
            $acumuladounidades=0;

            $productossimuladorunidades = Tb_rela_simulador::where('idSimulador','=',$idSimulacion)
            ->select('idProducto', 'unidades', 'idPrecio', 'idSimulador')->get();
            foreach($productossimuladorunidades as $productosimuladorunidades){
                $unidadessimuladorunidades = $productosimuladorunidades->unidades;

                $acumuladounidades=$acumuladounidades+$unidadessimuladorunidades;
            }

//---------------------Desde aca voy a recorrer todos los productos de la simulacion-----------------------//
            //sumapromedioponderado global

            $productosglobales = Tb_rela_simulador::where('idSimulador','=',$idSimulacion)
            ->select('idProducto', 'unidades', 'idPrecio', 'idSimulador')->get();

            $sumapromedioponderado=0;

            foreach($productosglobales as $productosglobal){
                $idproductoglobal = $productosglobal->idProducto;
                $unidadesglobal = $productosglobal->unidades;
                $idPrecioglobal = $productosglobal->idPrecio;

                $porcentajeparticipacion=($unidadesglobal/$acumuladounidades)*100;

                $productospreciosglobal = Tb_precios_venta::where('tb_precios_venta.idEmpresa','=',$idEmpresa)
                ->where('id','=',$idPrecioglobal)
                ->select('costo','cifunitario','porcentaje','costosfijos','materiaprima','manodeobradirecta','preciodeventa','detalle')->get();
                foreach($productospreciosglobal as $productopreciosglobal){
                    $productomateriaprima = $productopreciosglobal->materiaprima;
                    $productomanodeobradirecta = $productopreciosglobal->manodeobradirecta;

                    $productopreciodeventa = $productopreciosglobal->preciodeventa;

                    $costovariableunitario=$productomateriaprima+$productomanodeobradirecta;

                    $margencontribucion=$productopreciodeventa-$costovariableunitario;
                }

                $promedioponderado=($porcentajeparticipacion*$margencontribucion)/100;

                $sumapromedioponderado=$sumapromedioponderado+$promedioponderado;
            }

            $puntoequilibriototal=  ($acumuladocifglobal+$gastosfijos)/$sumapromedioponderado;
//----------------------Hasta aca, esto me sirve para traer datos globales de los productos----------------------//

//----------------------Desde aca vuelvo a recorrer productos para buscar datos individuales----------------------//

            $productossimulador = Tb_rela_simulador::where('idSimulador','=',$idSimulacion)
            ->select('idProducto', 'unidades', 'idPrecio', 'idSimulador')->get();
            foreach($productossimulador as $productosimulador){
                $idProductoSimulador = $productosimulador->idProducto;
                $unidadesSimulador = $productosimulador->unidades;
                $idPrecioSimulador = $productosimulador->idPrecio;
// hasta este punto saco los productos de la simulacion y uno a uno voy a calcularles los valores de materia mano cif porcentaje de las tablas nuevas
                $productosprecios = Tb_precios_venta::where('tb_precios_venta.idEmpresa','=',$idEmpresa)
                ->where('id','=',$idPrecioSimulador)
                ->select('costo','cifunitario','porcentaje','costosfijos','materiaprima','manodeobradirecta','preciodeventa','detalle')->get();
                foreach($productosprecios as $productoprecios){
                    $productocosto = $productoprecios->costo;
                    $productocifunitario = $productoprecios->cifunitario;
                    $productoporcentaje = $productoprecios->porcentaje;
                    $productocostosfijos = $productoprecios->costosfijos;
                    $productomateriaprima = $productoprecios->materiaprima;
                    $productomanodeobradirecta = $productoprecios->manodeobradirecta;
                    $productopreciodeventa = $productoprecios->preciodeventa;

                    $costovariableunitario=$productomateriaprima+$productomanodeobradirecta;
                    $costototal=$costovariableunitario+$productocifunitario;

                    $participacion=($unidadesSimulador/$acumuladounidades)*100;
                    $margencontribucion=$productopreciodeventa-$costovariableunitario;
                    $promedioponderado=($participacion*$margencontribucion)/100;
                    //var_dump($promedioponderado);
                    $puntoequilibrioproduccion=  ($participacion*$puntoequilibriototal)/100;

                    $tb_detallado_simulador=new Tb_detallado_simulador();
                    $tb_detallado_simulador->materiaprima=$productomateriaprima;
                    $tb_detallado_simulador->manodeobradirecta=$productomanodeobradirecta;
                    $tb_detallado_simulador->costovariableunitario=$costovariableunitario;
                    $tb_detallado_simulador->cifaterrizados=$productocifunitario;
                    $tb_detallado_simulador->costototal=$costototal;
                    $tb_detallado_simulador->porcentajeganancia=$productoporcentaje;
                    $tb_detallado_simulador->costosfijostotales=$acumuladocifglobal;
                    $tb_detallado_simulador->gastosfijos=$gastosfijos;
                    $tb_detallado_simulador->porcentajeparticipacion=$participacion;
                    $tb_detallado_simulador->unidadesavender=$unidadesSimulador;
                    $tb_detallado_simulador->precioventaunitario=$productopreciodeventa;
                    $tb_detallado_simulador->margencontribucion=$margencontribucion;
                    $tb_detallado_simulador->promedioponderado=$promedioponderado;
                    $tb_detallado_simulador->puntodeequilibriototal=$puntoequilibriototal;
                    $tb_detallado_simulador->puntodeequilibrioproducto=$puntoequilibrioproduccion;
                    $tb_detallado_simulador->idProducto=$idProductoSimulador;
                    $tb_detallado_simulador->idSimulador=$idSimulacion;
                    $tb_detallado_simulador->save();
                }

            }

        }


    public function unitarioEquilibrio(Request $request)
    {
        $identificador= $request->identificador;
        $simulacion= $request->simulacion;

        $total = 0;
        $acumuladomd = 0;
        $acumuladomi = 0;
        $acumuladomo = 0;

        $productos = Tb_producto::where('id','=',$identificador)
        ->select('producto','referencia','foto')->get();
        foreach($productos as $producto){
            $nombrep = $producto->producto;
            $referenciap = $producto->referencia;
            $fotop = $producto->foto;
            }

        $simulate = Tb_simulador::where('id','=',$simulacion)
        ->select('descripcion')
        ->get();
        foreach($simulate as $simula){
            $simuladet = $simula->descripcion;
            }

        //directa
        $query = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio),0) END) as acumuladomd");
        $materiadirecta = DB::table('tb_materia_prima_producto_simulador')
        ->select($query)
        ->where([
            ['tb_materia_prima_producto_simulador.idProducto','=',$identificador],
            ['tb_materia_prima_producto_simulador.idSimulacion','=',$simulacion],
            ['tb_materia_prima_producto_simulador.tipoDeCosto', '=', 'Directo'],
        ])->get();
        foreach($materiadirecta as $materiad){
        $acumuladomd = $materiad->acumuladomd + $acumuladomd;
        }

        //indirecta
        $query1 = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio),0) END) as acumuladomi");
        $materiaindirecta = DB::table('tb_materia_prima_producto_simulador')
        ->select($query1)
        ->where([
            ['tb_materia_prima_producto_simulador.idProducto','=',$identificador],
            ['tb_materia_prima_producto_simulador.idSimulacion','=',$simulacion],
            ['tb_materia_prima_producto_simulador.tipoDeCosto', '=', 'Indirecto'],
        ])->get();
        foreach($materiaindirecta as $materiaind){
            $acumuladomi = $materiaind->acumuladomi + $acumuladomi;
        }

        //manodeobra
        $query2 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto_simulador.tiempo*tb_mano_de_obra_producto_simulador.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_mano_de_obra_producto_simulador.tiempo*tb_mano_de_obra_producto_simulador.precio),0) END) as acumuladomo");
        $manodeobra = DB::table('tb_mano_de_obra_producto_simulador')
        ->select($query2)
        ->where([
            ['tb_mano_de_obra_producto_simulador.idProducto','=',$identificador],
            ['tb_mano_de_obra_producto_simulador.idSimulacion','=',$simulacion],
        ])->get();

        foreach($manodeobra as $manodeo){
            $acumuladomo = $manodeo->acumuladomo + $acumuladomo;
        }

        //cif
        $acumuladocift = 0;

        //cif
        $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif_simulador.valor) IS NULL THEN 0
        ELSE SUM(tb_concepto_cif_simulador.valor) END) as acumuladocif");
        $ciftotales = DB::table('tb_concepto_cif_simulador')
        ->where('tb_concepto_cif_simulador.idSimulacion', '=', $simulacion)
        ->select($query3)
        ->get();
        foreach($ciftotales as $ciftotal){
            $acumuladocift = $ciftotal->acumuladocif + $acumuladocift;
        }

        //capacidadproduccion producto
        $unidadprod=0;
        $tiempoprod=0;

        $queryu = DB::raw("tb_rela_simulador.unidades,Tb_rela_simulador.tiempo");
        $totalprodu = DB::table('tb_rela_simulador')
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

        $querys = DB::raw("SUM(tb_rela_simulador.unidades * tb_rela_simulador.tiempo) as acumuladocalc");
        $totalprod = DB::table('tb_rela_simulador')->where('idSimulacion','=',$simulacion)
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
//---------------------Cierre de funcion que voy a usar para almacenar lo que hago al calcular punto equ-----------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
public function cifTiempos($identificador)
{
    //el identificador que paso a esta función es el idSimulacion
    $acumuladocift = 0;

    //cif
    $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif_simulador.valor) IS NULL THEN 0
    ELSE SUM(tb_concepto_cif_simulador.valor) END) as acumuladocif");
    $ciftotales = DB::table('tb_concepto_cif_simulador')
    ->where('tb_concepto_cif_simulador.idSimulacion', '=', $identificador)
    ->select($query3)
    ->get();
    foreach($ciftotales as $ciftotal){
        $acumuladocift = $ciftotal->acumuladocif + $acumuladocift;
    }

    $acumuladoproduccion=0;
    $acumuladotiempo=0;
    $acumuladocalculo=0;

    $querys = DB::raw("SUM(tb_rela_simulador.unidades) as acumuladoprod,SUM(tb_rela_simulador.tiempo) as acumuladotiem,
    SUM(tb_rela_simulador.unidades * tb_rela_simulador.tiempo) as acumuladocalc");
    $totalprod = DB::table('tb_rela_simulador')
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
    $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
    ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto',
    'tb_producto.referencia','tb_producto.descripcion','tb_producto.id as idProducto')
    ->where('tb_rela_simulador.idSimulacion', '=', $identificador)
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
    $identificador= $request->identificador;
    $simulacion= $request->simulacion;

    $total = 0;
    $acumuladomd = 0;
    $acumuladomi = 0;
    $acumuladomo = 0;

    $productos = Tb_producto::where('id','=',$identificador)
    ->select('producto','referencia','foto')->get();
    foreach($productos as $producto){
        $nombrep = $producto->producto;
        $referenciap = $producto->referencia;
        $fotop = $producto->foto;
        }

    $simulate = Tb_simulador::where('id','=',$simulacion)
    ->select('descripcion')
    ->get();
    foreach($simulate as $simula){
        $simuladet = $simula->descripcion;
        }

    //directa
    $query = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio) IS NULL THEN 0
    ELSE ROUND(SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio),0) END) as acumuladomd");
    $materiadirecta = DB::table('tb_materia_prima_producto_simulador')
    ->select($query)
    ->where([
        ['tb_materia_prima_producto_simulador.idProducto','=',$identificador],
        ['tb_materia_prima_producto_simulador.idSimulacion','=',$simulacion],
        ['tb_materia_prima_producto_simulador.tipoDeCosto', '=', 'Directo'],
    ])->get();
    foreach($materiadirecta as $materiad){
    $acumuladomd = $materiad->acumuladomd + $acumuladomd;
    }

    //indirecta
    $query1 = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio) IS NULL THEN 0
    ELSE ROUND(SUM(tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio),0) END) as acumuladomi");
    $materiaindirecta = DB::table('tb_materia_prima_producto_simulador')
    ->select($query1)
    ->where([
        ['tb_materia_prima_producto_simulador.idProducto','=',$identificador],
        ['tb_materia_prima_producto_simulador.idSimulacion','=',$simulacion],
        ['tb_materia_prima_producto_simulador.tipoDeCosto', '=', 'Indirecto'],
    ])->get();
    foreach($materiaindirecta as $materiaind){
        $acumuladomi = $materiaind->acumuladomi + $acumuladomi;
    }

    //manodeobra
    $query2 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto_simulador.tiempo*tb_mano_de_obra_producto_simulador.precio) IS NULL THEN 0
    ELSE ROUND(SUM(tb_mano_de_obra_producto_simulador.tiempo*tb_mano_de_obra_producto_simulador.precio),0) END) as acumuladomo");
    $manodeobra = DB::table('tb_mano_de_obra_producto_simulador')
    ->select($query2)
    ->where([
        ['tb_mano_de_obra_producto_simulador.idProducto','=',$identificador],
        ['tb_mano_de_obra_producto_simulador.idSimulacion','=',$simulacion],
    ])->get();

    foreach($manodeobra as $manodeo){
        $acumuladomo = $manodeo->acumuladomo + $acumuladomo;
    }

    //cif
    $acumuladocift = 0;

    //cif
    $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif_simulador.valor) IS NULL THEN 0
    ELSE SUM(tb_concepto_cif_simulador.valor) END) as acumuladocif");
    $ciftotales = DB::table('tb_concepto_cif_simulador')
    ->where('tb_concepto_cif_simulador.idSimulacion', '=', $simulacion)
    ->select($query3)
    ->get();
    foreach($ciftotales as $ciftotal){
        $acumuladocift = $ciftotal->acumuladocif + $acumuladocift;
    }

    //capacidadproduccion producto
    $unidadprod=0;
    $tiempoprod=0;

    $queryu = DB::raw("tb_rela_simulador.unidades,Tb_rela_simulador.tiempo");
    $totalprodu = DB::table('tb_rela_simulador')
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

    $querys = DB::raw("SUM(tb_rela_simulador.unidades * tb_rela_simulador.tiempo) as acumuladocalc");
    $totalprod = DB::table('tb_rela_simulador')->where('idSimulacion','=',$simulacion)
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
    $identificador= $request->identificador;
    $simulacion= $request->simulacion;

    $materiaprimaproductos = Tb_materia_prima_producto_simulador::join("tb_gestion_materia_prima","tb_materia_prima_producto_simulador.idMateriaPrima","=","tb_gestion_materia_prima.id")
    ->leftJoin('tb_unidad_base',function($join){
        $join->on('tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id');
    })
    ->select('tb_gestion_materia_prima.gestionMateria','tb_materia_prima_producto_simulador.tipoDeCosto',
    DB::raw('ROUND((tb_materia_prima_producto_simulador.cantidad*tb_materia_prima_producto_simulador.precio),0) as subtotal'))
    ->where([
        ['tb_materia_prima_producto_simulador.idProducto','=',$identificador],
        ['tb_materia_prima_producto_simulador.idSimulacion','=',$simulacion],
    ])
    ->orderBy('tb_gestion_materia_prima.id','desc')
    ->get();

    # Modelo::join('tablaqueseune',basicamente un on)
    $manodeobraproductos = Tb_mano_de_obra_producto_simulador::join("tb_perfil","tb_mano_de_obra_producto_simulador.idPerfil","=","tb_perfil.id")
    ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
    ->leftJoin('tb_area',function($join){
        $join->on('tb_proceso.idArea','=','tb_area.id');
    })
    ->select('tb_perfil.perfil','tb_proceso.proceso',DB::raw('ROUND((tb_mano_de_obra_producto_simulador.tiempo*tb_mano_de_obra_producto_simulador.precio),0) as subtotal'))
    ->where([
        ['tb_mano_de_obra_producto_simulador.idProducto','=',$identificador],
        ['tb_mano_de_obra_producto_simulador.idSimulacion','=',$simulacion],
    ])
    ->orderBy('tb_mano_de_obra_producto_simulador.id','desc')
    ->get();

    $conceptos = Tb_concepto_cif_simulador::where([
        ['tb_concepto_cif_simulador.idSimulacion', '=', $simulacion],
        ['tb_concepto_cif_simulador.valor','>','0'],
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
->select('tb_producto.producto as producto','tb_producto.referencia as referencia','tb_producto.foto as foto','tb_hoja_de_costo.capacidadMensual as capacidadMensual')
->where('tb_producto.id','=',$identificador)
->get();

foreach($productos as $producto){
$nombrep = $producto->producto;
$referenciap = $producto->referencia;
$fotop = $producto->foto;
$unidadesprod = $producto->capacidadMensual;
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
