<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsuarioEstudiante extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_usuario_estudiante';
    protected $table = 'usuario_estudiante';
    protected $fillable = [
        'id_usuario_estudiante',
        'usuario_estudiante',
        'usuario_estudiante_password',
        'usuario_estudiante_creacion',
        'usuario_estudiante_estado',
        'id_persona',
        'usuario_estudiante_perfil_url',
    ];

    // model persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = auth()->id();
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = auth()->id();
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = auth()->id();
    //         $model->save();
    //     });
    // }
}
