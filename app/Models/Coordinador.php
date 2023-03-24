<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinador extends Model
{
    use HasFactory;

    protected $primaryKey = "coordinador_id";

    protected $table = 'coordinador';
    protected $fillable = [
        'coordinador_id',
        'trabajador_id',
        'facultad_id',
        'categoria_docente',
        'coordinador_estado',
    ];

    public $timestamps = false;

    // Trabajador
    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'trabajador_id','trabajador_id');
    }

    // Facultad
    public function facultad(){
        return $this->belongsTo(Facultad::class,
        'facultad_id','facultad_id');
    }
}
