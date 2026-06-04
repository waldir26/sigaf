<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';
    protected $primaryKey = 'id_programa';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'estado'
    ];
}