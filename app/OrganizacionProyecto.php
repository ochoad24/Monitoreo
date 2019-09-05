<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganizacionProyecto extends Model
{
    //
    protected $fillable = [
        'idproyecto',
        'idorganizacion'
    ];
    protected $table = 'organizaciones_proyecto';
    public $timestamps = false;
}
