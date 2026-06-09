<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $table = 'participantes';
    protected $primaryKey = 'id_participante';
    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'edad',
        'telefono',
        'correo',
        'direccion',
        'sexo'
    ];
}
