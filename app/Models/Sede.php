<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $primaryKey = "id_sede";
    protected $table = 'sede';
    protected $fillable = [
        'id_sede',
        'sede',
        'sede_estado'
    ];

    public $timestamps = false;

    // Programa
    public function programa()
    {
        return $this->hasMany(Programa::class, 'id_sede');
    }
}
