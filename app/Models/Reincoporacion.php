<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reincoporacion extends Model
{
    use HasFactory;

    protected $primaryKey = "id_reincorporacion";
    protected $table = "reincorporacion";
    protected $fillable = [
        'id_reincorporacion',
        'id_admitido',
        'reincorporacion_resolucion',
        'reincorporacion_resolucion_url',
        'reincorporacion_estado'
    ];

    public function admitido()
    {
        return $this->belongsTo(Admitido::class, 'id_admitido', 'id_admitido');
    }
}
