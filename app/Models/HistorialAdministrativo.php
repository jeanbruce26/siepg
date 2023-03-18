<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialAdministrativo extends Model
{
    use HasFactory;

    protected $primaryKey = "historial_administrativo_id";

    protected $table = 'historial_administrativo';
    protected $fillable = [
        'historial_administrativo_id',
        'usuario_id',
        'trabajador_id',
        'historial_descripcion',
        'historial_tabla',
        'historial_usuario_id',
        'historial_fecha',
    ];

    public $timestamps = false;
}
