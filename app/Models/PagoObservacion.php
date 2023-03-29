<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoObservacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pago_observacion';
    protected $table = 'pago_observacion';
    protected $fillable = [
        'id_pago_observacion',
        'pago_observacion',
        'id_pago',
        'pago_observacion_creacion',
        'pago_observacion_estado',
    ];

    public $timestamps = false;

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }
}
