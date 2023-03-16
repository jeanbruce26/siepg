<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_uni";
    protected $table = 'univer';
    protected $fillable = [
        'cod_uni',
        'universidad',
        'depart',
        'tipo_gesti',
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class, 'univer_cod_uni');
    }
}
