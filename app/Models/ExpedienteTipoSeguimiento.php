<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteTipoSeguimiento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente_tipo_seguimiento';
    protected $table = 'expediente_tipo_seguimiento';
    protected $fillable = [
        'id_expediente_tipo_seguimiento',
        'id_expediente',
        'id_tipo_seguimiento',
        'expediente_tipo_seguimiento_estado'
    ];

    public $timestamps = false;

    // modelo expediente
    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'id_expediente', 'id_expediente');
    }

    // modelo tipo_seguimiento
    public function tipo_seguimiento()
    {
        return $this->belongsTo(TipoSeguimiento::class, 'id_tipo_seguimiento', 'id_tipo_seguimiento');
    }
}
