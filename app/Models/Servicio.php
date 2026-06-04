<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios_actividades';
    protected $primaryKey = 'id_servicio';
    public $timestamps = false;

    protected $fillable = [
        'tipo_servicio', 'descripcion', 'responsable', 'fecha', 'monto', 'comprobante'
    ];
}