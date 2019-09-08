<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoActividad extends Model
{
    //
    protected $fillable = [
        'id',
        'nombre',
        'estado'
    ];
    protected $table = 'tipo_actividad';
    public $timestamps = false;
}
