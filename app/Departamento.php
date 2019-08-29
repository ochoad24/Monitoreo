<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //
    protected $fillable = [
        'departamento'
    ];

    public function organizacion() {
        return $this->hasMany('\App\Organizacion');
    }
}
