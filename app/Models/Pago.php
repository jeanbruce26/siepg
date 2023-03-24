<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pago extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = "id_pago";
    protected $table = 'pago';
    protected $fillable = [
        'id_pago',
        'pago_documento',
        'pago_operacion',
        'pago_monto',
        'pago_fecha',
        'pago_estado',
        'pago_verificacion',
        'pago_voucher_url',
        'id_canal_pago'
    ];

    public $timestamps = false;

    public function canal_pago(){
        return $this->belongsTo(CanalPago::class,
        'id_canal_pago','id_canal_pago');
    }

    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_pago','id_pago');
    }
}
