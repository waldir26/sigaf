<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoFinanciero extends Model
{
    protected $table = 'movimientos_financieros';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'origen',
        'monto',
        'fecha',
        'descripcion',
        'tabla_referencia',
        'id_referencia'
    ];
}
