<?php

namespace App\Http\Controllers;

use App\Tb_puntos_equilibrio;
use App\tb_precios_venta;
use App\Tb_producto;
use App\Tb_liquidez;
use App\Tb_rentabilidad;
use App\Tb_endeudamiento;
use App\Tb_rotacioninventario;
use App\Tb_rotacioncartera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Tb_simulacionesController extends Controller
{
    //
    public function storePrecioVenta(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $idProducto=$request->idProducto;
        $porcentaje=$request->porcentaje;
        $cifunitario=$request->cifunitario;
        $costosfijos=$request->costosfijos;
        $materiaprima=$request->materiaprima;
        $manodeobradirecta=$request->manodeobradirecta;
        $costo=$request->costo;
        $valorparcial=(100-$porcentaje)/100;
        if($valorparcial==0){
            $precioventa=$costo;
        }
        else{
            $precioventa=$costo/$valorparcial;
        }

        $producto='';

        $productos = Tb_producto::where('tb_producto.id','=',$idProducto)
        ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
        ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
        ->select('tb_producto.producto')->get();
        foreach($productos as $product){
            $producto = $product->producto;
        }

        $detalle=$producto."-".$hoy;

        $tb_precios_venta=new Tb_precios_venta();
        $tb_precios_venta->idProducto=$idProducto;
        $tb_precios_venta->costo=$costo;
        $tb_precios_venta->cifunitario=$cifunitario;
        $tb_precios_venta->porcentaje=$porcentaje;
        $tb_precios_venta->preciodeventa=$precioventa;
        $tb_precios_venta->costosfijos=$costosfijos;
        $tb_precios_venta->materiaprima=$materiaprima;
        $tb_precios_venta->manodeobradirecta=$manodeobradirecta;
        $tb_precios_venta->idEmpresa=$idEmpresa;
        $tb_precios_venta->detalle=$detalle;

        $tb_precios_venta->save();
    }

    public function listarPrecios()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $precios = Tb_precios_venta::where('tb_precios_venta.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$precios->total(),
                'current_page'  =>$precios->currentPage(),
                'per_page'      =>$precios->perPage(),
                'last_page'     =>$precios->lastPage(),
                'from'          =>$precios->firstItem(),
                'to'            =>$precios->lastItem(),
            ],
                'precios' => $precios
        ];
    }

    public function storePuntoEquilibrio(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $idProducto=$request->idProducto;
        $preciodeventa=$request->preciodeventa;
        $costosfijos=$request->costosfijos;
        $gastosfijos=$request->gastosfijos;
        $costosygastos=$costosfijos+$gastosfijos;
        $materiaprima=$request->materiaprima;
        $manodeobradirecta=$request->manodeobradirecta;
        $costosvariables=$materiaprima+$manodeobradirecta;
        $valorparcial1=$preciodeventa-$costosvariables;
        if($valorparcial1==0){
            $puntoequilibrio=1;
        }
        else{
            $puntoequilibrio=$costosygastos/$valorparcial1;
        }

        $producto='';

        $productos = Tb_producto::join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
        ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
        ->where('tb_producto.id','=',$idProducto)->select('tb_producto.producto')->get();
        foreach($productos as $product){
            $producto = $product->producto;
        }

        $detalle=$producto."-".$hoy;

        $tb_puntos_equilibrio=new Tb_puntos_equilibrio();
        $tb_puntos_equilibrio->idProducto=$idProducto;
        $tb_puntos_equilibrio->preciodeventa=$preciodeventa;
        $tb_puntos_equilibrio->costosfijos=$costosfijos;
        $tb_puntos_equilibrio->gastosfijos=$gastosfijos;
        $tb_puntos_equilibrio->materiaprima=$materiaprima;
        $tb_puntos_equilibrio->manodeobradirecta=$manodeobradirecta;
        $tb_puntos_equilibrio->puntodeequilibrio=$puntoequilibrio;
        $tb_puntos_equilibrio->idEmpresa=$idEmpresa;
        $tb_puntos_equilibrio->detalle=$detalle;

        $tb_puntos_equilibrio->save();
    }

    public function listarPuntos()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $puntos = Tb_puntos_equilibrio::where('tb_puntos_equilibrio.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$puntos->total(),
                'current_page'  =>$puntos->currentPage(),
                'per_page'      =>$puntos->perPage(),
                'last_page'     =>$puntos->lastPage(),
                'from'          =>$puntos->firstItem(),
                'to'            =>$puntos->lastItem(),
            ],
                'puntos' => $puntos
        ];
    }

    public function storeLiquidez(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $activocorriente=$request->activocorriente;
        $pasivocorriente=$request->pasivocorriente;
        $inventario=$request->inventario;

        $capitaldetrabajo=$activocorriente-$pasivocorriente;

        if($pasivocorriente==0){
            $razoncorriente=0;
            $pruebaacida=0;
        }
        else{
            $razoncorriente=$activocorriente/$pasivocorriente;
            $pruebaacida=($activocorriente-$inventario)/$pasivocorriente;
        }

        $producto='Indicadores de liquidez';

        $detalle=$producto."-".$hoy;

        $tb_liquidez=new Tb_liquidez();
        $tb_liquidez->activocorriente=$activocorriente;
        $tb_liquidez->pasivocorriente=$pasivocorriente;
        $tb_liquidez->razoncorriente=$razoncorriente;
        $tb_liquidez->capitaldetrabajo=$capitaldetrabajo;
        $tb_liquidez->inventario=$inventario;
        $tb_liquidez->pruebaacida=$pruebaacida;
        $tb_liquidez->detalle=$detalle;
        $tb_liquidez->idEmpresa=$idEmpresa;
        $tb_liquidez->save();
    }

    public function listarLiquidez()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $liquidez = Tb_liquidez::where('tb_liquidez.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$liquidez->total(),
                'current_page'  =>$liquidez->currentPage(),
                'per_page'      =>$liquidez->perPage(),
                'last_page'     =>$liquidez->lastPage(),
                'from'          =>$liquidez->firstItem(),
                'to'            =>$liquidez->lastItem(),
            ],
                'liquidez' => $liquidez
        ];
    }

    public function storeEndeudamiento(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $activototal=$request->activototal;
        $pasivototal=$request->pasivototal;
        $pasivocorriente=$request->pasivocorriente;
        $patrimoniototal=$request->patrimoniototal;

        if($activototal==0){
            $endeudamientototal=0;
        }
        else{
            $endeudamientototal=($pasivototal/$activototal)*100;
        }

        if($patrimoniototal==0){
            $leverage=0;
        }
        else{
            $leverage=($pasivototal/$patrimoniototal)*100;
        }

        if($pasivototal==0){
            $cortoplazo=0;
        }
        else{
            $cortoplazo=($pasivocorriente/$pasivototal)*100;
        }

        $producto='Indicadores de endeudamiento';

        $detalle=$producto."-".$hoy;

        $tb_endeudamiento=new Tb_endeudamiento();
        $tb_endeudamiento->activototal=$activototal;
        $tb_endeudamiento->pasivototal=$pasivototal;
        $tb_endeudamiento->pasivocorriente=$pasivocorriente;
        $tb_endeudamiento->patrimoniototal=$patrimoniototal;
        $tb_endeudamiento->endeudamientototal=$endeudamientototal;
        $tb_endeudamiento->leverage=$leverage;
        $tb_endeudamiento->cortoplazo=$cortoplazo;
        $tb_endeudamiento->detalle=$detalle;
        $tb_endeudamiento->idEmpresa=$idEmpresa;
        $tb_endeudamiento->save();
    }

    public function listarEndeudamiento()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $endeudamiento = Tb_endeudamiento::where('tb_endeudamiento.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$endeudamiento->total(),
                'current_page'  =>$endeudamiento->currentPage(),
                'per_page'      =>$endeudamiento->perPage(),
                'last_page'     =>$endeudamiento->lastPage(),
                'from'          =>$endeudamiento->firstItem(),
                'to'            =>$endeudamiento->lastItem(),
            ],
                'endeudamiento' => $endeudamiento
        ];
    }

    public function storeRentabilidad(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $utilidadbruta=$request->utilidadbruta;
        $utilidadoperacional=$request->utilidadoperacional;
        $utilidadneta=$request->utilidadneta;
        $ingresostotales=$request->ingresostotales;

        if($ingresostotales==0){
            $margenbruto=0;
            $margenoperacional=0;
            $margenneto=0;
        }
        else{
            $margenbruto=($utilidadbruta/$ingresostotales)*100;
            $margenoperacional=($utilidadoperacional/$ingresostotales)*100;
            $margenneto=($utilidadneta/$ingresostotales)*100;
        }

        $producto='Indicadores de rentabilidad';

        $detalle=$producto."-".$hoy;

        $tb_rentabilidad=new Tb_rentabilidad();
        $tb_rentabilidad->utilidadbruta=$utilidadbruta;
        $tb_rentabilidad->utilidadoperacional=$utilidadoperacional;
        $tb_rentabilidad->utilidadneta=$utilidadneta;
        $tb_rentabilidad->ingresostotales=$ingresostotales;
        $tb_rentabilidad->margenbruto=$margenbruto;
        $tb_rentabilidad->margenoperacional=$margenoperacional;
        $tb_rentabilidad->margenneto=$margenneto;
        $tb_rentabilidad->detalle=$detalle;
        $tb_rentabilidad->idEmpresa=$idEmpresa;
        $tb_rentabilidad->save();
    }

    public function listarRentabilidad()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $rentabilidad = Tb_rentabilidad::where('tb_rentabilidad.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$rentabilidad->total(),
                'current_page'  =>$rentabilidad->currentPage(),
                'per_page'      =>$rentabilidad->perPage(),
                'last_page'     =>$rentabilidad->lastPage(),
                'from'          =>$rentabilidad->firstItem(),
                'to'            =>$rentabilidad->lastItem(),
            ],
                'rentabilidad' => $rentabilidad
        ];
    }

    public function storeRotacioninventario(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $fechainicial=$request->fechainicial;
        $fechafinal=$request->fechafinal;
        $tipoperiodo=$request->tipoperiodo;
        $saldoperiodoactual=$request->saldoperiodoactual;
        $saldoperiodoanterior=$request->saldoperiodoanterior;
        $costodeventas=$request->costodeventas;

        $sumasaldos=$saldoperiodoactual+$saldoperiodoanterior;
        $promediosaldos=$sumasaldos/2;

        if($promediosaldos==0){
            $rotacioninventario=0;
        }
        else{
            $rotacioninventario=$costodeventas/$promediosaldos;
        }

        $producto='Rotación de inventario';

        $detalle=$producto."-".$hoy;

        $tb_rotacioninventario=new Tb_rotacioninventario();
        $tb_rotacioninventario->fechainicial=$fechainicial;
        $tb_rotacioninventario->fechafinal=$fechafinal;
        $tb_rotacioninventario->tipoperiodo=$tipoperiodo;
        $tb_rotacioninventario->saldoperiodoactual=$saldoperiodoactual;
        $tb_rotacioninventario->saldoperiodoanterior=$saldoperiodoanterior;
        $tb_rotacioninventario->costodeventas=$costodeventas;
        $tb_rotacioninventario->sumasaldos=$sumasaldos;
        $tb_rotacioninventario->promediosaldos=$promediosaldos;
        $tb_rotacioninventario->rotacioninventario=$rotacioninventario;
        $tb_rotacioninventario->detalle=$detalle;
        $tb_rotacioninventario->idEmpresa=$idEmpresa;
        $tb_rotacioninventario->save();

    }

    public function listarRotacioninventario()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $rotacioninventario = Tb_rotacioninventario::where('tb_rotacioninventario.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$rotacioninventario->total(),
                'current_page'  =>$rotacioninventario->currentPage(),
                'per_page'      =>$rotacioninventario->perPage(),
                'last_page'     =>$rotacioninventario->lastPage(),
                'from'          =>$rotacioninventario->firstItem(),
                'to'            =>$rotacioninventario->lastItem(),
            ],
                'rotacioninventario' => $rotacioninventario
        ];
    }

    public function storeRotacioncartera(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $hoy = date("Y-m-d");

        $fechainicial=$request->fechainicial;
        $fechafinal=$request->fechafinal;
        $tipoperiodo=$request->tipoperiodo;
        $saldoperiodoactual=$request->saldoperiodoactual;
        $saldoperiodoanterior=$request->saldoperiodoanterior;
        $costodeventas=$request->costodeventas;

        $sumasaldos=$saldoperiodoactual+$saldoperiodoanterior;
        $promediosaldos=$sumasaldos/2;

        if($promediosaldos==0){
            $rotacioncartera=0;
        }
        else{
            $rotacioncartera=$costodeventas/$promediosaldos;
        }

        $producto='Rotación de cartera';

        $detalle=$producto."-".$hoy;

        $tb_rotacioncartera=new Tb_rotacioncartera();
        $tb_rotacioncartera->fechainicial=$fechainicial;
        $tb_rotacioncartera->fechafinal=$fechafinal;
        $tb_rotacioncartera->tipoperiodo=$tipoperiodo;
        $tb_rotacioncartera->saldoperiodoactual=$saldoperiodoactual;
        $tb_rotacioncartera->saldoperiodoanterior=$saldoperiodoanterior;
        $tb_rotacioncartera->costodeventas=$costodeventas;
        $tb_rotacioncartera->sumasaldos=$sumasaldos;
        $tb_rotacioncartera->promediosaldos=$promediosaldos;
        $tb_rotacioncartera->rotacioncartera=$rotacioncartera;
        $tb_rotacioncartera->detalle=$detalle;
        $tb_rotacioncartera->idEmpresa=$idEmpresa;
        $tb_rotacioncartera->save();

    }

    public function listarRotacioncartera()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $rotacioncartera = Tb_rotacioncartera::where('tb_rotacioncartera.idEmpresa','=',$idEmpresa)
        ->orderBy('id','desc')->paginate(5);

        return [
        'pagination' => [
                'total'         =>$rotacioncartera->total(),
                'current_page'  =>$rotacioncartera->currentPage(),
                'per_page'      =>$rotacioncartera->perPage(),
                'last_page'     =>$rotacioncartera->lastPage(),
                'from'          =>$rotacioncartera->firstItem(),
                'to'            =>$rotacioncartera->lastItem(),
            ],
                'rotacioncartera' => $rotacioncartera
        ];
    }

    public function posibles()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $posibles = Tb_precios_venta::where('tb_precios_venta.idEmpresa','=',$idEmpresa)
        ->orderBy('detalle','asc')->get();

        return [
                'posibles' => $posibles
        ];
    }

    public function unitariogen(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $idProducto=$request->identificador;

        $puntos = Tb_precios_venta::where('id','=',$idProducto)->orderBy('id','asc')->get();

        foreach($puntos as $totalg){

            $idProducto= $totalg->idProducto;
            $costopar= $totalg->costo;
            $porcentaje= $totalg->porcentaje;
            $acumuladocift= $totalg->costosfijos;
            $acumuladomp= $totalg->materiaprima;
            $acumuladomo= $totalg->manodeobradirecta;
            $precioventa= $totalg->preciodeventa;
            $detalle= $totalg->detalle;
        }
        return [
            'idProducto'    => $idProducto,
            'costopar'      => $costopar,
            'porcentaje'    => $porcentaje,
            'acumuladocift' => $acumuladocift,
            'acumuladomp'   => $acumuladomp,
            'acumuladomo'   => $acumuladomo,
            'precioventa'   => $precioventa,
            'detalle'       => $detalle
        ];
    }


}
