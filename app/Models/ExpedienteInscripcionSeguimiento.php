<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteInscripcionSeguimiento extends Model
{
    use HasFactory;

    protected $primaryKey = 'expediente_inscripcion_seguimiento_id';
    protected $table = 'expediente_inscripcion_seguimiento';
    protected $fillable = [
        'expediente_inscripcion_seguimiento_id',
        'cod_ex_insc',
        'tipo_seguimiento',
        'expediente_inscripcion_seguimiento_estado'
    ];

    public $timestamps = false;

    public function expediente_inscripcion()
    {
        return $this->belongsTo(ExpedienteInscripcion::class, 'cod_ex_insc');
    }
}
