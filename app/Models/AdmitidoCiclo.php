<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmitidoCiclo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_admitido_ciclo';
    protected $table = 'admitido_ciclo';
    protected $fillable = [
        'id_admitido_ciclo',
        'id_admitido',
        'id_ciclo',
        'admitido_ciclo_estado'
    ];

    public $timestamps = false;

    // admitido
    public function admitido()
    {
        return $this->belongsTo(Admitido::class, 'id_admitido');
    }

    // ciclo
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'id_ciclo');
    }
}
