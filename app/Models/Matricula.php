<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_matricula';
    protected $table = 'matricula';
    protected $fillable = [
        'id_matricula',
        'matricula_codigo',
        'matricula_ficha_url',
        'matricula_fecha_creacion',
        'matricula_estado',
        'id_admitido',
        'id_programa_proceso_grupo',
        'id_ciclo',
        'id_pago'
    ];

    public $timestamps = false;

    // admitido
    public function admitido()
    {
        return $this->belongsTo(Admitido::class, 'id_admitido');
    }

    // programa proceso grupo
    public function programa_proceso_grupo()
    {
        return $this->belongsTo(ProgramaProcesoGrupo::class, 'id_programa_proceso_grupo');
    }

    // ciclo
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'id_ciclo');
    }

    // pago
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago');
    }
}
