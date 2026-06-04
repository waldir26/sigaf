<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donante extends Model
{
    protected $table = 'donantes';
    protected $primaryKey = 'id_donante';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'telefono', 'correo', 'direccion'
    ];
}