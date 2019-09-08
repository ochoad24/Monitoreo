<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actividad;
use Carbon\Carbon;
class ActividadController extends Controller
{
    public function index(Request $request){
        $proyecto=$request->proyecto;
        return Actividad::join('proyectos','proyectos.IdProyecto','=','actividades.idProyecto')->select('actividades.id','actividades.actividad','actividades.tareas','actividades.tareasCompletadas','actividades.tareasPendientes','actividades.fechaInicio','actividades.fechaFinal','actividades.estado')->where('actividades.idProyecto',$proyecto)->orderBy('actividades.created_at', 'desc')->get();
    }
    public function edit(Request $request){
        $id=$request->id;
        try{
            $actividad = Actividad::findOrFail($id);
            $actividad->actividad = $request->actividad;
            $actividad->idProyecto = $request->idProyecto;
            $actividad->fechaInicio = Carbon::parse($request->fechaInicio);
            $actividad->fechaFinal = Carbon::parse($request->fechaFinal);
            $actividad->tareas = 15;
            $actividad->tareasCompletadas = 5;
            $actividad->tareasPendientes = 10;
            $actividad->Estado = 1;
            $actividad->save();
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
    public function drop (Permiso $permiso){
        try{
            $permiso->delete();
            return 'Se ha eliminado el permiso correctamente';
        }catch(\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
       
    }
    public function store(Request $request){
        //Registrar una nueva actividad
        try{
            $actividad = new Actividad();
            $actividad->actividad = $request->actividad;
            $actividad->idProyecto = $request->idProyecto;
            $actividad->fechaInicio = Carbon::parse($request->fechaInicio);
            $actividad->fechaFinal = Carbon::parse($request->fechaFin);
            $actividad->tareas = 15;
            $actividad->tareasCompletadas = 5;
            $actividad->tareasPendientes = 10;
            $actividad->estado = 1;
            $actividad->save();
        }catch(\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    public function activate(Request $request) {
        //
        $id=$request->id;
        try{
            $actividad=Actividad::findOrFail($id);
            $actividad->estado=1;
            $actividad->save();
            return 'Se ha activado correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }

    public function deactivate(Request $request) {
        $id=$request->id;
        try{
            $actividad=Actividad::findOrFail($id);
            $actividad->estado=0;
            $actividad->save();
            return 'Se ha desactivado correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }

    public function destroy(Request $request)
    {
        //
        $actividad = Actividad::findOrFail($request->id);
        try {
            $actividad->delete();
            return response()->json(array('success' => true, 'id' => $org->IdOrganizacion), 200);
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }
}
