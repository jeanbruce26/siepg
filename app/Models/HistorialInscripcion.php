<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInscripcion extends Model
{
    use HasFactory;

    protected $primaryKey = 'historial_inscripcion_id';
    protected $table = 'historial_inscripcion';
    protected $fillable = [
        'historial_inscripcion_id',
        'persona_documento',
        'id_inscripcion',
        'admision',
        'programa',
        'historial_inscripcion_fecha',
        'admitido',
    ];

    public $timestamps = false;
}
