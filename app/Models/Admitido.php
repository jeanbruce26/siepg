<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admitido extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_admitido';
    protected $table = 'admitido';
    protected $fillable = [
        'id_admitido',
        'admitido_codigo',
        'id_persona',
        'id_evaluacion',
        'admitido_estado'
    ];

    public $timestamps = false;

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class,
        'id_evaluacion','id_evaluacion');
    }

    // Persona
    public function persona(){
        return $this->belongsTo(Persona::class,
        'id_persona','id_persona');
    }
}
