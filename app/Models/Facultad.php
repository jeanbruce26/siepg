<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_facultad';
    protected $table = 'facultad';
    protected $fillable = [
        'id_facultad',
        'facultad',
        'facultad_asignado',
        'facultad_estado',
    ];

    public $timestamps = false;

    // SubPrograma
    public function subprograma()
    {
        return $this->hasMany(SubPrograma::class, 'id_facultad', 'id_facultad');
    }

    // coordinador
    public function coordinador()
    {
        return $this->hasMany(Coordinador::class, 'id_facultad', 'id_facultad');
    }
}
