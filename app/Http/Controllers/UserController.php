<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
class UserController extends Controller
{
    public function index(Request $request){
        return Permiso::join('roles','roles.id','=','permisos.idRol')->select('permisos.id','permisos.nombre','permisos.idRol','roles.nombre as rol')->orderBy('permisos.created_at', 'desc')->get();
    }
    public function edit(Request $request){
        $id=$request->id;
        $nombre=$request->nombre;
        $idRol=$request->idRol;
        try{
            $permisos=Permiso::findOrFail($id);
            $permisos->nombre=$nombre;
            $permisos->idRol=$idRol;
            $permisos->save();
            return 'Se ha modificado el permiso correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
    public function drop (Permiso $permiso){
        try{
            $permiso->delete();
            return 'Se ha eliminado el permiso correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
       
    }
    public function store(Request $request){
        $nombre=$request->nombre;
        $idRol=$request->idRol;
        try{
            $permiso=new Permiso;
            $permiso->nombre=$nombre;
            $permiso->idRol=$idRol;
            $permiso->save();
            return 'Se ha agregado el permiso correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
}
