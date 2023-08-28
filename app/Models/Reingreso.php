<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reingreso extends Model
{
    use HasFactory;

    protected $primaryKey = "id_reingreso";
    protected $table = "reingreso";
    protected $fillable = [
        'id_reingreso',
        'id_admitido',
        'id_programa_proceso',
        'id_programa_proceso_antiguo',
        'id_tipo_reingreso',
        'reingreso_resolucion',
        'reingreso_resolucion_url',
        'reingreso_fecha_creacion',
        'reingreso_estado',
    ];

    public $timestamps = false;

    // admitido
    public function admitido(): BelongsTo
    {
        return $this->belongsTo(Admitido::class, 'id_admitido');
    }

    // programa proceso
    public function programa_proceso(): BelongsTo
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso');
    }

    // programa proceso antiguo
    public function programa_proceso_antiguo(): BelongsTo
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso_antiguo', 'id_programa_proceso');
    }

    // tipo reingreso
    public function tipo_reingreso(): BelongsTo
    {
        return $this->belongsTo(TipoReingreso::class, 'id_tipo_reingreso');
    }
}
