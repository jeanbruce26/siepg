<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_inscripcion';
    protected $table = 'inscripcion';
    protected $fillable = [
        'id_inscripcion',
        'inscripcion_codigo',
        'inscripcion_ficha_url',
        'inscripcion_fecha',
        'id_persona',
        'id_concepto_pago',
        'id_pago',
        'id_programa_proceso',
        'inscripcion_tipo_programa',
    ];

    public $timestamps = false;

    // Persona
    public function persona(){
        return $this->belongsTo(Persona::class, 'id_persona','id_persona');
    }

    // concepto pago
    public function concepto_pago(){
        return $this->belongsTo(ConceptoPago::class, 'id_concepto_pago','id_concepto_pago');
    }

    // pago
    public function pago(){
        return $this->belongsTo(Pago::class, 'id_pago','id_pago');
    }

    // Programa Proceso
    public function programa_proeso(){
        return $this->hasMany(ProgramaProceso::class, 'id_programa_proceso','id_programa_proceso');
    }

    // Inscripcion Expediente
    public function expediente_inscripcion(){
        return $this->hasMany(ExpedienteInscripcion::class, 'id_inscripcion','id_inscripcion');
    }

    // Evaluacion
    public function evaluacion(){
        return $this->hasOne(Evaluacion::class, 'id_inscripcion','id_inscripcion');
    }
}
