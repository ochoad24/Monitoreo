<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request){
        return Rol::orderBy('created_at', 'desc')->get();
    }
    public function edit(Request $request){
        $id=$request->id;
        $nombre=$request->nombre;
        try{

            $rol=Rol::findOrFail($id);
            $rol->nombre=$nombre;
            $rol->save();
            return 'Se ha modificado el rol correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
    public function drop(Rol $rol){
        try{
            $rol->delete();
            return 'Se ha eliminado el rol correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
       
    }
    public function store(Request $request){
        $nombre=$request->nombre;
        try{
            $rol=new Rol;
            $rol->nombre=$nombre;
            $rol->save();
            return 'Se ha agregado el rol correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
}
