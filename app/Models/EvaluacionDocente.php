<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionDocente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_docente';
    protected $table = 'evaluacion_docente';
    protected $fillable = [
        'id_evaluacion_docente',
        'id_nota_matricula_curso',
        'id_docente',
        'id_admitido',
        'evaluacion_docente_estado',
    ];

    // relaciones
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function nota_matricula_curso(): BelongsTo
    {
        return $this->belongsTo(NotaMatriculaCurso::class, 'id_nota_matricula_curso', 'id_nota_matricula_curso');
    }

    public function admitido(): BelongsTo
    {
        return $this->belongsTo(Admitido::class, 'id_admitido', 'id_admitido');
    }
}
