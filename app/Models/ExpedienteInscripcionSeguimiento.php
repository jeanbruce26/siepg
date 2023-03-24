<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteInscripcionSeguimiento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente_inscripcion_seguimiento';
    protected $table = 'expediente_inscripcion_seguimiento';
    protected $fillable = [
        'id_expediente_inscripcion_seguimiento',
        'id_expediente_inscripcion',
        'tipo_seguimiento',
        'expediente_inscripcion_seguimiento_estado'
    ];

    public $timestamps = false;

    public function expediente_inscripcion()
    {
        return $this->belongsTo(ExpedienteInscripcion::class, 'id_expediente_inscripcion', 'id_expediente_inscripcion');
    }
}
