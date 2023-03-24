<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subprograma extends Model
{
    use HasFactory;

    protected $primaryKey = "id_subprograma";
    protected $table = 'subprograma';
    protected $fillable = [
        'id_subprograma',
        'subprograma_codigo',
        'subprograma',
        'subprograma_estado',
        'id_programa',
        'id_facultad'
    ];

    public $timestamps = false;

    // Programa
    public function programa(){
        return $this->belongsTo(Programa::class,
        'id_programa','id_programa');
    }

    // Facultad
    public function facultad(){
        return $this->belongsTo(Facultad::class,
        'id_facultad','id_facultad');
    }

    // Mencion
    public function mencion(){
        return $this->hasMany(Mencion::class,
        'id_subprograma','id_subprograma');
    }
}
