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

    public function sede(){
        return $this->belongsTo(Sede::class,
        'id_sede','cod_sede');
    }
}
