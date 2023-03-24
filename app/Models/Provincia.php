<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $primaryKey = "id_provincia";
    protected $table = 'provincia';
    protected $fillable = [
        'id_provincia',
        'provincia',
        'ubigeo',
        'id_departamento',
    ];

    public $timestamps = false;

    // Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class,
        'id_departamento',  'id_departamento');
    }

    // Distrito
    public function distrito()
    {
        return $this->hasMany(Distrito::class,
        'id_provincia', 'id_provincia');
    }
}
