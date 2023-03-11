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
        'persona_idpersona',
        'estado',
        'admision_cod_admi',
        'id_mencion',
        'inscripcion',
        'fecha_inscripcion',
        'tipo_programa'
    ];

    public $timestamps = false;

    // Persona
    public function persona(){
        return $this->belongsTo(Persona::class,
        'persona_idpersona','idpersona');
    }

    // Admision
    public function admision(){
        return $this->belongsTo(Admision::class,
        'admision_cod_admi','cod_admi');
    }

    // Mencion
    public function mencion(){
        return $this->belongsTo(Mencion::class,
        'id_mencion','id_mencion');
    }

    // Inscripcion Pago
    public function inscripcion_pago(){
        return $this->hasMany(InscripcionPago::class,
        'inscripcion_id','id_inscripcion');
    }
}
