<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;

    protected $fillable = [
        'id_participante',
        'id_programa',
        'fecha_inscripcion',
        'estado',
        'tipo_inscripcion',
        'id_escuela'
    ];

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'id_participante', 'id_participante');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id_programa');
    }

    public function escuela()
    {
        return $this->belongsTo(Escuela::class, 'id_escuela', 'id_escuela');
    }
}