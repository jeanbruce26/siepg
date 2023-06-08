<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaCurso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_matricula_curso';
    protected $table = 'matricula_curso';
    protected $fillable = [
        'id_matricula_curso',
        'id_matricula',
        'id_curso_programa_proceso',
        'matricula_curso_fecha_creacion',
        'matricula_curso_estado'
    ];

    public $timestamps = false;

    // matricula
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'id_matricula');
    }

    // curso programa plan
    public function curso_programa_plan()
    {
        return $this->belongsTo(CursoProgramaPlan::class, 'id_curso_programa_plan');
    }
}
