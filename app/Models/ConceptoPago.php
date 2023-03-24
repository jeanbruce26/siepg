<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoPago extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_concepto_pago';
    protected $table = 'concepto_pago';
    protected $fillable = [
        'id_concepto_pago',
        'concepto_pago',
        'concepto_pago_monto',
        'concepto_pago_estado',
    ];

    public $timestamps = false;

    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_concepto_pago','id_concepto_pago');
    }
}
