<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;

    protected $primaryKey = "id_universidad";
    protected $table = 'universidad';
    protected $fillable = [
        'id_universidad',
        'universidad',
        'universidad_estado',
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class,
        'id_universidad', 'id_universidad');
    }
}
