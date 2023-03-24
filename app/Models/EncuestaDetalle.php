<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaDetalle extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_encuesta_detalle';
    protected $table = 'encuesta_detalle';
    protected $fillable = [
        'id_encuesta_detalle',
        'documento',
        'id_encuesta',
        'otros',
        'encuesta_detalle_estado',
    ];

    public $timestamps = false;

    // Encuesta
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id_encuesta');
    }
}
