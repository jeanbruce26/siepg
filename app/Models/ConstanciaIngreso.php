<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstanciaIngreso extends Model
{
    use HasFactory;

    use HasFactory;

    protected $primaryKey = 'id_constancia_ingreso';
    protected $table = 'constancia_ingreso';
    protected $fillable = [
        'id_constancia_ingreso',
        'constancia_ingreso_codigo',
        'constancia_ingreso_url',
        'constancia_ingreso_fecha',
        'id_pago',
        'id_admitido',
        'constancia_ingreso_estado'
    ];

    public $timestamps = false;

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago');
    }

    public function admitido()
    {
        return $this->belongsTo(Admitido::class, 'id_admitido');
    }
}
