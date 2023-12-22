<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_usuario';
    protected $table = 'usuario';
    protected $fillable = [
        'id_usuario',
        'usuario_nombre',
        'usuario_correo',
        'usuario_password',
        'id_trabajador_tipo_trabajador',
        'usuario_estado',
    ];

    // Trabajador Tipo Trabajador
    public function trabajador_tipo_trabajador(){
        return $this->belongsTo(TrabajadorTipoTrabajador::class,
        'id_trabajador_tipo_trabajador','id_trabajador_tipo_trabajador');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
            $model->save();
        });
    }
}
