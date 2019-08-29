<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    //
    protected $table = 'organizaciones';
    protected $primaryKey = 'IdOrganizacion';
    protected $fillable = [
        'IdDepartamento', 'nombre', 'municipio'
    ];

    public function departamento() {
        return $this->belongsTo('\App\Departamento');
    }
}
