<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pago extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = "pago_id";
    protected $table = 'pago';
    protected $fillable = [
        'pago_id',
        'dni',
        'nro_operacion',
        'monto',
        'fecha_pago',
        'estado',
        'canal_pago_id',
        'verificacion_pago',
        'voucher'
    ];

    public $timestamps = false;

    public function canal_pago(){
        return $this->belongsTo(CanalPago::class,
        'canal_pago_id','canal_pago_id');
    }

    public function inscripcion_pago(){
        return $this->hasMany(InscripcionPago::class,
        'pago_id','pago_id');
    }
}
