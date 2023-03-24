<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteAdmision extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente_admision';
    protected $table = 'expediente_admision';
    protected $fillable = [
        'id_expediente_admision',
        'id_expediente',
        'id_admision',
        'expediente_admision_estado',
    ];

    public $timestamps = false;

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'id_expediente', 'id_expediente');
    }

    public function admision()
    {
        return $this->belongsTo(Admision::class, 'id_admision', 'id_admision');
    }

    public function expediente_inscripcion()
    {
        return $this->hasMany(ExpedienteInscripcion::class, 'id_expediente_admision', 'id_expediente_admision');
    }
}
