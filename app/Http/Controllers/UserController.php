<?php

namespace App\Http\Controllers;

use App\User;
use App\Tb_rol;
use App\Tb_usuario_tiene_rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\Notification;
use Illuminate\Support\Str; 

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;

        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if ($buscar=='') {
            $usuarios = User::join("tb_usuario_tiene_rol","tb_usuario_tiene_rol.idUser","=","users.id")
            ->leftJoin('tb_rol',function($join){
                $join->on('tb_usuario_tiene_rol.idRol','=','tb_rol.id');
            })
            ->select('users.id','users.name','users.email','users.estado','tb_rol.id as idRol','tb_rol.rol','tb_rol.estado as estado_rol','tb_usuario_tiene_rol.id as idexRol')
            ->where('tb_usuario_tiene_rol.idEmpresa','=',$idEmpresa)
            ->orderBy('users.id','desc')->paginate(5);
        }
        else if($criterio=='name'){
            $usuarios = User::join("tb_usuario_tiene_rol","tb_usuario_tiene_rol.idUser","=","users.id")
            ->leftJoin('tb_rol',function($join){
                $join->on('tb_usuario_tiene_rol.idRol','=','tb_rol.id');
            })
            ->select('users.id','users.name','users.email','users.estado','tb_rol.id as idRol','tb_rol.rol','tb_rol.estado as estado_rol','tb_usuario_tiene_rol.id as idexRol')
            ->where('tb_usuario_tiene_rol.idEmpresa','=',$idEmpresa)
            ->where('users.name', 'like', '%'. $buscar . '%')
            ->orderBy('users.id','desc')->paginate(5);
        }
        else if($criterio=='rol'){
            $usuarios = User::join("tb_usuario_tiene_rol","tb_usuario_tiene_rol.idUser","=","users.id")
            ->leftJoin('tb_rol',function($join){
                $join->on('tb_usuario_tiene_rol.idRol','=','tb_rol.id');
            })
            ->select('users.id','users.name','users.email','users.estado','tb_rol.id as idRol','tb_rol.rol','tb_rol.estado as estado_rol','tb_usuario_tiene_rol.id as idexRol')
            ->where('tb_usuario_tiene_rol.idEmpresa','=',$idEmpresa)
            ->where('tb_rol.rol', 'like', '%'. $buscar . '%')
            ->orderBy('users.id','desc')->paginate(5);
        }
        else if($criterio=='email'){
            $usuarios = User::join("tb_usuario_tiene_rol","tb_usuario_tiene_rol.idUser","=","users.id")
            ->leftJoin('tb_rol',function($join){
                $join->on('tb_usuario_tiene_rol.idRol','=','tb_rol.id');
            })
            ->select('users.id','users.name','users.email','users.estado','tb_rol.id as idRol','tb_rol.rol','tb_rol.estado as estado_rol','tb_usuario_tiene_rol.id as idexRol')
            ->where('tb_rol.rol', 'like', '%'. $buscar . '%')
            ->where('users.email', 'like', '%'. $buscar . '%')
            ->orderBy('users.id','desc')->paginate(5);
        }
        else {
                $usuarios = User::join("tb_usuario_tiene_rol","tb_usuario_tiene_rol.idUser","=","users.id")
                ->leftJoin('tb_rol',function($join){
                    $join->on('tb_usuario_tiene_rol.idRol','=','tb_rol.id');
                })
                ->select('users.id','users.name','users.email','users.estado','tb_rol.id as idRol','tb_rol.rol','tb_rol.estado as estado_rol','tb_usuario_tiene_rol.id as idexRol')
                ->where('tb_rol.rol', 'like', '%'. $buscar . '%')
                ->where('users.id', 'like', '%'. $buscar . '%')
                ->orderBy('users.id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$usuarios->total(),
                'current_page'  =>$usuarios->currentPage(),
                'per_page'      =>$usuarios->perPage(),
                'last_page'     =>$usuarios->lastPage(),
                'from'          =>$usuarios->firstItem(),
                'to'            =>$usuarios->lastItem(),
            ],
                'usuarios' => $usuarios
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $password = Str::random(8);
        
        $users=new User();
        $users->name=$request->name;
        $users->email=$request->email;
        $users->password=bcrypt($password);
        $users->save();

        $idtabla=DB::getPdo()->lastInsertId();
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
           $idEmpresa=$empresa['id'];
        }
        //cambios multiempresa
        $usersrela=new Tb_usuario_tiene_rol();
        $usersrela->idUser=$idtabla;
        $usersrela->idRol=$request->idRol;
        $usersrela->idEmpresa=$idEmpresa; //cambios multiempresa
        $usersrela->save();

        Mail::to($users->email)->send(new Notification($users->name, $users->email, $password));

        return response()->json(['message' => 'Usuario creado y correo enviado.', 'user' => $users]);
    }

    public function update(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $users=User::findOrFail($request->id);
        $users->name=$request->name;
        $users->email=$request->email;
        $users->estado='1';
        $users->save();

        $userrela=Tb_usuario_tiene_rol::findOrFail($request->idexRol);
        $userrela->idUser=$request->id;
        $userrela->idRol=$request->idRol;
        $userrela->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $users=User::findOrFail($request->id);
        $users->estado='0';
        $users->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $users=User::findOrFail($request->id);
        $users->estado='1';
        $users->save();
    }

    public function eliminarUsuario($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Acción no permitida'], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verifica si el usuario tiene el rol de superadministrador (rol 1)
        $esSuperAdmin = \DB::table('tb_usuario_tiene_rol')
            ->where('idUser', $id)
            ->where('idRol', 1)
            ->exists();

        if ($esSuperAdmin) {
            return response()->json(['error' => 'No se puede eliminar un superadministrador'], 403);
        }

        // Elimina los roles asociados al usuario (solo si no es superadministrador)
        \DB::table('tb_usuario_tiene_rol')->where('idUser', $id)->delete();

        
        $user->delete();

        return response()->json(['message' => 'Usuario y roles asociados eliminados correctamente'], 200);
    }

    public function cambiarContrasena(Request $request)
    {
    
        $request->validate([
            'newPassword' => 'required|min:8',
        ]);
        
        $user = Auth::user();

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json(['message' => 'Contraseña cambiada correctamente.']);
    }

}
