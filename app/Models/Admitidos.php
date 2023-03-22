<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admitidos extends Model
{
    use HasFactory;

    protected $primaryKey = "admitidos_id";
    protected $table = 'admitidos';
    protected $fillable = [
        'admitidos_id',
        'admitidos_codigo',
        'persona_id',
        'evaluacion_id',
        'constancia_codigo',
        'constancia'
    ];

    public $timestamps = false;

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class,
        'evaluacion_id','evaluacion_id');
    }

    // Persona
    public function persona(){
        return $this->belongsTo(Persona::class,
        'idpersona','persona_id');
    }
}
