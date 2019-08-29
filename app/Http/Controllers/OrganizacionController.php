<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organizacion;
use App\Departamento;
use Carbon\Carbon;

class OrganizacionController extends Controller
{
    //
    public function index()
    {
        //
        $organizaciones = Organizacion::join('departamentos', 'organizaciones.IdDepartamento', '=', 'departamentos.id')
        ->select('organizaciones.IdOrganizacion', 'organizaciones.nombre', 'organizaciones.municipio', 'departamentos.departamento')->get();
        return $organizaciones;
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
        $organizacion = new Organizacion();
        $organizacion->IdDepartamento = $request->IdDepartamento;
        $organizacion->nombre = $request->nombre;
        $organizacion->municipio = $request->municipio;
        if($organizacion->save()) {
            return response()->json(array('success' => true, 'id' => $organizacion->IdOrganizacion), 200);
        } else {
            return response()->json(array('success' => false), 200);
        }

    }

    public function depart() {
        $departamentos = Departamento::all();
        return $departamentos;
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
        $organizacion = Organizacion::findOrFail($request->id);
        $organizacion->IdDepartamento = $request->IdDepartamento;
        $organizacion->nombre = $request->nombre;
        $organizacion->municipio = $request->municipio;
        if($organizacion->save()) {
            return response()->json(array('success' => true, 'id' => $organizacion->IdOrganizacion), 200);
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
        try {
            $res = Organizacion::where('IdOrganizacion', $id)->delete();
            return ['success' => $res];
        } catch (\Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }
}
