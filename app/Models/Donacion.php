<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{
    protected $table = 'donaciones';
    protected $primaryKey = 'id_donacion';
    public $timestamps = false;

    protected $fillable = [
        'id_donante', 'tipo_donacion', 'monto', 'descripcion', 'fecha', 'comprobante'
    ];
}