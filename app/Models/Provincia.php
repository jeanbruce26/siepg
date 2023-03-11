<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = 'provincia';
    protected $fillable = [
        'id',
        'provincia',
        'ubigeo',
        'id_departamento',
    ];

    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function distrito()
    {
        return $this->hasMany(Distrito::class, 'id_provincia');
    }
}
