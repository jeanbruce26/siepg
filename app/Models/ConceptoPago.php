<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoPago extends Model
{
    use HasFactory;

    protected $primaryKey = "concepto_id";
    protected $table = 'concepto_pago';
    protected $fillable = [
        'concepto_id',
        'concepto',
        'monto',
        'estado',
    ];

    public $timestamps = false;

    public function inscripcion_pago(){
        return $this->hasMany(InscripcionPago::class,
        'concepto_pago_id','concepto_id');
    }
}
