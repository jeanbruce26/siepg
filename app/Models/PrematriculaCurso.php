<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrematriculaCurso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_prematrícula_curso';
    protected $table = 'prematricula_curso';
    protected $fillable = [
        'id_prematriícula_curso',
        'id_prematricula',
        'id_curso_programa_plan',
        'prematricula_curso_fecha_creacion',
        'prematricula_curso_estado'
    ];

    public $timestamps = false;

    // prematrícula
    public function prematricula()
    {
        return $this->belongsTo(Prematricula::class, 'id_prematricula');
    }

    // curso programa plan
    public function curso_programa_plan()
    {
        return $this->belongsTo(CursoProgramaPlan::class, 'id_curso_programa_plan');
    }
}
