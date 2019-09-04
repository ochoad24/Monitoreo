<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\OrganizacionProyecto;
use App\Organizacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $proyectos = Proyecto::all();
        return $proyectos;
    }

    public function orgs(Request $request) {
        $orgs = Proyecto::join('organizaciones_proyecto', 'organizaciones_proyecto.idproyecto', '=', 'proyectos.IdProyecto')
        ->join('organizaciones', 'organizaciones.IdOrganizacion', '=', 'organizaciones_proyecto.idorganizacion')
        ->where('proyectos.IdProyecto', '=', $request->id)->get();
        return $orgs;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $proyecto = new Proyecto();
            $proyecto->Titulo = $request->Titulo;
            $proyecto->Descripcion = $request->Descripcion;
            $proyecto->FechaInicio = Carbon::parse($request->FechaInicio);
            $proyecto->FechaFin = Carbon::parse($request->FechaFin);
            $proyecto->save();

            $orgs = $request->data;//Array de las organizaciones

            foreach($orgs as $ep=>$org) {
                $org_proy = new OrganizacionProyecto();
                $org_proy->idproyecto = $proyecto->IdProyecto;
                $org_proy->idorganizacion = $org['IdOrganizacion'];
                $org_proy->save();
            }
            DB::commit();
        }
        catch(\Throwable $th) {
            DB::rollback();
            return ['error' => $th->getMessage()];
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $proyecto = Proyecto::findOrFail($request->id);
        $proyecto->Titulo = $request->Titulo;
        $proyecto->Descripcion = $request->Descripcion;
        $proyecto->FechaInicio = Carbon::parse($request->FechaInicio);
        $proyecto->FechaFin = Carbon::parse($request->FechaFin);
        $proyecto->Estado = $request->Estado;
        if($proyecto->save()) {
            return response()->json(array('success' => true, 'id' => $proyecto->IdProyecto), 200);
        } else {
            return response()->json(array('success' => false), 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request) {
        //
        $id=$request->id;
        try{
            $proyecto=Proyecto::findOrFail($id);
            $proyecto->Estado=1;
            $proyecto->save();
            return 'Se ha activado correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }

    public function deactivate(Request $request) {
        $id=$request->id;
        try{
            $proyecto=Proyecto::findOrFail($id);
            $proyecto->Estado=0;
            $proyecto->save();
            return 'Se ha desactivado correctamente';
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
}
