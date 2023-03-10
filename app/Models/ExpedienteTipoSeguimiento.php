<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteTipoSeguimiento extends Model
{
    use HasFactory;

    protected $primaryKey = 'expediente_tipo_seguimiento_id';
    protected $table = 'expediente_tipo_seguimiento';
    protected $fillable = [
        'expediente_tipo_seguimiento_id',
        'cod_exp',
        'tipo_seguimiento',
        'expediente_tipo_seguimiento_estado'
    ];

    public $timestamps = false;

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'cod_exp');
    }
}
