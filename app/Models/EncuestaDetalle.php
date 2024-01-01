<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaDetalle extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_encuesta_detalle';
    protected $table = 'encuesta_detalle';
    protected $fillable = [
        'id_encuesta_detalle',
        'id_persona',
        'id_admision',
        'id_encuesta',
        'otros',
        'encuesta_detalle_estado',
        'encuesta_detalle_creacion',
    ];

    public $timestamps = false;

    // Encuesta
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id_encuesta');
    }

    // Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    // Admision
    public function admision()
    {
        return $this->belongsTo(Admision::class, 'id_admision', 'id_admision');
    }
}
