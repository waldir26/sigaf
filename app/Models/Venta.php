<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas_bienes';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;

    protected $fillable = [
        'bien', 'monto', 'fecha', 'comprobante'
    ];
}