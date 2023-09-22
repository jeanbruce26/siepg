<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaMatriculaCurso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_nota_matricula_curso';
    protected $table = 'nota_matricula_curso';
    protected $fillable = [
        'id_nota_matricula_curso',
        'id_matricula_curso',
        'nota_evaluacion_permanente',
        'nota_evaluacion_medio_curso',
        'nota_evaluacion_final',
        'nota_promedio_final',
        'nota_observacion',
        'nota_matricula_curso_fecha_creacion',
        'nota_matricula_curso_estado',
        'id_estado_cursos',
        'id_docente'
    ];

    public $timestamps = false;

    // matricula curso
    public function matricula_curso()
    {
        return $this->belongsTo(MatriculaCurso::class, 'id_matricula_curso');
    }

    // estado curso
    public function estado_cursos()
    {
        return $this->belongsTo(EstadoCursos::class, 'id_estado_cursos');
    }

    // docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }
}
