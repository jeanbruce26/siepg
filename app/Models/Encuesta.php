<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_encuesta';
    protected $table = 'encuesta';
    protected $fillable = [
        'id_encuesta',
        'encuesta',
        'encuesta_estado',
    ];

    public $timestamps = false;

    // Encuesta Detalle
    public function encuesta_detalle()
    {
        return $this->hasMany(EncuestaDetalle::class, 'id_encuesta', 'id_encuesta');
    }
}
