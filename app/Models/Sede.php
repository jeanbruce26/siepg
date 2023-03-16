<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_sede";
    protected $table = 'sede';
    protected $fillable = [
        'cod_sede',
        'sede',
        'sede_estado'
    ];
    
    public $timestamps = false;
}
