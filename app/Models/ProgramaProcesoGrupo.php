<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaProcesoGrupo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_programa_proceso_grupo';
    protected $table = 'programa_proceso_grupo';
    protected $fillable = [
        'id_programa_proceso_grupo',
        'grupo_detalle',
        'grupo_cantidad',
        'programa_proceso_grupo_estado',
        'id_programa_proceso'
    ];

    public $timestamps = false;

    // programa proceso
    public function programa_proceso()
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso');
    }
}
