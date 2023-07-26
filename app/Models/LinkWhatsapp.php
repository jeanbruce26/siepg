<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkWhatsapp extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_link_whatsapp';
    protected $table = 'link_whatsapp_programas';
    protected $fillable = [
        'id_link_whatsapp',
        'link_whatsapp',
        'id_programa_proceso',
        'id_admision',
        'link_whatsapp_fecha_creacion',
        'link_whatsapp_estado',
    ];

    public $timestamps = false;

    // programa_proceso
    public function programa_proceso()
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso');
    }

    // admision
    public function admision()
    {
        return $this->belongsTo(Admision::class, 'id_admision');
    }
}
