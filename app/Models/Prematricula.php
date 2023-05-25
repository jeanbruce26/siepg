<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prematricula extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_prematricula';
    protected $table = 'prematricula';
    protected $fillable = [
        'id_prematricula',
        'prematricula_fecha_creacion',
        'prematricula_estado',
        'id_admitido'
    ];

    public $timestamps = false;

    // admitido
    public function admitido()
    {
        return $this->belongsTo(Admitido::class, 'id_admitido');
    }
}
