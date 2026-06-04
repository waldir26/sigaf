<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
    protected $table = 'escuelas_beneficiarias';
    protected $primaryKey = 'id_escuela';
    public $timestamps = false;

    protected $fillable = [
        'nombre_escuela', 'director', 'municipio', 'cantidad_estudiantes', 'id_programa'
    ];
}