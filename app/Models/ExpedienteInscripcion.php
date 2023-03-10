<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteInscripcion extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_ex_insc";
    protected $table = 'ex_insc';
    protected $fillable = [
        'cod_ex_insc',
        'nom_exped',
        'estado',
        'observacion',
        'fecha_entre',
        'expediente_cod_exp',
        'id_inscripcion'
    ];

    public $timestamps = false;

    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class,
        'id_inscripcion','id_inscripcion');
    }

    public function expediente(){
        return $this->belongsTo(Expediente::class,
        'expediente_cod_exp','cod_exp');
    }

    public function expediente_inscripcion_seguimiento()
    {
        return $this->hasMany(ExpedienteInscripcionSeguimiento::class, 'cod_ex_insc');
    }
}
