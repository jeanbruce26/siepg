<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSeguimiento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_seguimiento';
    protected $table = 'tipo_seguimiento';
    protected $fillable = [
        'id_tipo_seguimiento',
        'tipo_seguimiento',
        'tipo_seguimiento_titulo',
        'tipo_seguimiento_descripcion',
        'tipo_seguimiento_estado',
        'tipo_seguimiento_fecha_creacion'
    ];

    public $timestamps = false;
}
