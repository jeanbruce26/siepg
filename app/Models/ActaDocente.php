<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActaDocente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_acta_docente';
    protected $table = 'acta_docente';
    protected $fillable = [
        'id_acta_docente',
        'acta_url',
        'id_docente_curso',
        'acta_docente_fecha_creacion',
        'acta_docente_estado',
    ];

    public $timestamps = false;

    // docente curso
    public function docente_curso(): BelongsTo
    {
        return $this->belongsTo(DocenteCurso::class, 'id_docente_curso');
    }
}
