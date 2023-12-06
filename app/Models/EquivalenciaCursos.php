<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquivalenciaCursos extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_equivalencia';
    protected $table = 'equivalencia_cursos';
    protected $fillable = [
        'id_equivalencia',
        'id_curso',
        'id_curso_equivalente',
        'equivalencia_resolucion',
        'equivalencia_resolucion_url',
        'equivalencia_fecha_creacion',
        'equivalencia_estado'
    ];

    public $timestamps = false;

    // curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    // curso equivalente
    public function curso_equivalente(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso_equivalente', 'id_curso');
    }
}
