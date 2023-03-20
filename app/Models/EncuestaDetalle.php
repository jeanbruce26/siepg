<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaDetalle extends Model
{
    use HasFactory;

    protected $primaryKey = 'encuesta_detalle_id';
    protected $table = 'encuesta_detalle';
    protected $fillable = [
        'encuesta_detalle_id',
        'documento',
        'encuesta_id',
        'otros',
        'encuesta_detalle_estado',
    ];

    public $timestamps = false;

    // Encuesta Detalle
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id');
    }
}
