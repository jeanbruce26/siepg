<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $primaryKey = "id_programa";
    protected $table = 'programa';
    protected $fillable = [
        'id_programa',
        'descripcion_programa',
        'id_sede',
    ];

    public $timestamps = false;

    // Sede
    public function sede(){
        return $this->belongsTo(Sede::class,
        'id_sede','cod_sede');
    }
    // Subprograma
    public function subprograma(){
        return $this->hasMany(Subprograma::class,
        'id_programa','id_programa');
    }
}
