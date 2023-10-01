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
        'id_docente',
        'acta_docente_fecha_creacion',
        'acta_docente_estado',
    ];

    public $timestamps = false;

    // docente
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }
}
