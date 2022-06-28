<?php

namespace App\Http\Controllers;

use App\Tb_vista_personalizada;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tb_vista_personalizadaController extends Controller
{
    //
    public function index()
    {
        $idUser=Auth::user()->id;

        /*
        return [
                //'Rol' => $idRol
                'User' => $idUser
            ];
        */

        //
        $vistapersonalizada = Tb_vista_personalizada::where('tb_vista_personalizada.idUser','=',$idUser)->get();

        foreach($vistapersonalizada as $c){
            $idVista=$c->id;
            $escritorio=$c->escritorio;
            $documentacion=$c->documentacion;
            $administracion=$c->administracion;
            $conceptosCif=$c->conceptosCif;
            $materiales=$c->materiales;
            $productos=$c->productos;
            $produccion=$c->produccion;
            $kardex=$c->kardex;
            $manoDeObra=$c->manoDeObra;
            $personas=$c->personas;
            $nomina=$c->nomina;
            $gestionFinanciera=$c->gestionFinanciera;
            $idUser=$c->idUser;
        }

        return [
            'idVista' => $idVista,
            'escritorio' => $escritorio,
            'documentacion' => $documentacion,
            'administracion' => $administracion,
            'conceptosCif' => $conceptosCif,
            'materiales' => $materiales,
            'productos' => $productos,
            'produccion' => $produccion,
            'kardex' => $kardex,
            'manoDeObra' => $manoDeObra,
            'personas' => $personas,
            'nomina' => $nomina,
            'gestionFinanciera' => $gestionFinanciera,
            'idUser' => $idUser
        ];

        /*
        return [
            'registro' => $vistapersonalizada
        ];
        */
    }

    public function actualizar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $tb_vista_personalizada=Tb_vista_personalizada::findOrFail($request->id);
        $tb_vista_personalizada->escritorio=$request->escritorio;
        $tb_vista_personalizada->documentacion=$request->documentacion;
        $tb_vista_personalizada->administracion=$request->administracion;
        $tb_vista_personalizada->conceptosCif=$request->conceptosCif;
        $tb_vista_personalizada->materiales=$request->materiales;
        $tb_vista_personalizada->productos=$request->productos;
        $tb_vista_personalizada->produccion=$request->produccion;
        $tb_vista_personalizada->kardex=$request->kardex;
        $tb_vista_personalizada->manoDeObra=$request->manoDeObra;
        $tb_vista_personalizada->personas=$request->personas;
        $tb_vista_personalizada->nomina=$request->nomina;
        $tb_vista_personalizada->gestionFinanciera=$request->gestionFinanciera;
        $tb_vista_personalizada->idUser=$request->idUser;
        $tb_vista_personalizada->save();


    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_vista_personalizada=new Tb_vista_personalizada();
        $tb_vista_personalizada->escritorio=$request->escritorio;
        $tb_vista_personalizada->documentacion=$request->documentacion;
        $tb_vista_personalizada->administracion=$request->administracion;
        $tb_vista_personalizada->conceptosCif=$request->conceptosCif;
        $tb_vista_personalizada->materiales=$request->materiales;
        $tb_vista_personalizada->productos=$request->productos;
        $tb_vista_personalizada->produccion=$request->produccion;
        $tb_vista_personalizada->kardex=$request->kardex;
        $tb_vista_personalizada->manoDeObra=$request->manoDeObra;
        $tb_vista_personalizada->personas=$request->personas;
        $tb_vista_personalizada->nomina=$request->nomina;
        $tb_vista_personalizada->gestionFinanciera=$request->gestionFinanciera;
        $tb_vista_personalizada->idUser=$request->idUser;
        $tb_vista_personalizada->save();
    }

    public function pruebas()
    {
        $idUser=Auth::user()->vistas;

        return [
            'pruebas' => $idUser
        ];
    }

}
