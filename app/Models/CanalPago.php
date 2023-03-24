<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanalPago extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_canal_pago';
    protected $table = 'canal_pago';
    protected $fillable = [
        'id_canal_pago',
        'canal_pago',
        'canal_pago_estado'
    ];

    public $timestamps = false;

    public function pago(){
        return $this->hasMany(Pago::class,
        'id_canal_pago','id_canal_pago');
    }
}
