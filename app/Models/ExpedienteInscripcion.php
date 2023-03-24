<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteInscripcion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente_inscripcion';
    protected $table = 'expediente_inscripcion';
    protected $fillable = [
        'id_expediente_inscripcion',
        'expediente_inscripcion_url',
        'expediente_inscripcion_estado',
        'expediente_inscripcion_fecha',
        'id_expediente_admision',
        'id_inscripcion'
    ];

    public $timestamps = false;

    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion','id_inscripcion');
    }

    public function expediente_admision(){
        return $this->belongsTo(ExpedienteAdmision::class, 'id_expediente_admision','id_expediente_admision');
    }

    public function expediente_inscripcion_seguimiento()
    {
        return $this->hasMany(ExpedienteInscripcionSeguimiento::class, 'id_expediente_inscripcion', 'id_expediente_inscripcion');
    }
}
