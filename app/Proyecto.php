<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    //
    protected $primaryKey = 'IdProyecto';
    protected $fillable = ['Titulo', 'Descripcion', 'FechaInicio', 'FechaFin', 'Estado'];
}
