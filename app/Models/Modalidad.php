<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    use HasFactory;

    protected $primaryKey = "id_modalidad";
    protected $table = 'modalidad';
    protected $fillable = [
        'id_modalidad',
        'modalidad',
        'modalidad_estado',
    ];

    public function programa_proceso(){
        return $this->hasMany(ProgramaProceso::class,
        'id_modalidad','id_modalidad');
    }
}
