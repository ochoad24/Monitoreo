<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use Carbon\Carbon;

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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $proyecto = new Proyecto();
        $proyecto->Titulo = $request->Titulo;
        $proyecto->Descripcion = $request->Descripcion;
        $proyecto->FechaInicio = Carbon::parse($request->FechaInicio);
        $proyecto->FechaFin = Carbon::parse($request->FechaFin);
        if($proyecto->save()) {
            return response()->json(array('success' => true, 'id' => $proyecto->IdProyecto), 200);
        } else {
            return response()->json(array('success' => false), 200);
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
    public function destroy($id)
    {
        //
    }
    public function select()
    {
        //
        $proyectos = Proyecto::select('idProyecto as id','Titulo as nombre')->get();
        return $proyectos;
    }
}
