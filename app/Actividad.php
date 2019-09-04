<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table='actividades';
    protected $fillable = ['id','actividad','tareas','tareasCompletadas','tareasPendientes','fechaInicio','fechaFinal','estado','idProyecto'];
}
