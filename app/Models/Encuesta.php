<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $primaryKey = 'encuesta_id';
    protected $table = 'encuesta';
    protected $fillable = [
        'encuesta_id',
        'descripcion',
        'encuesta_estado',
    ];

    public $timestamps = false;

    // Encuesta Detalle
    public function encuesta_detalle()
    {
        return $this->hasMany(EncuestaDetalle::class, 'encuesta_id');
    }
}
