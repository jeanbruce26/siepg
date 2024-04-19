<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioEvaluacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_usuario_evaluacion';
    protected $table = 'usuario_evaluacion';
    protected $fillable = [
        'id_usuario_evaluacion',
        'id_usuario',
        'id_programa_proceso',
        'numero_inicio',
        'numero_fin',
        'usuario_evaluacion_estado',
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function programa_proceso(){
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso', 'id_programa_proceso');
    }
}
