<?php

namespace App\Jobs;

use App\Models\Docente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessEnvioCredencialesDocentes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id_docente;
    public $value;

    public function __construct($id_docente, $value)
    {
        $this->id_docente = $id_docente;
        $this->value = $value;
    }

    public function handle(): void
    {
        //Enviar las credenciales al correo
        $docente = Docente::where('id_docente', $this->id_docente)->first();
        $trabajador = $docente->trabajador;
        $trabajador_tipo_trabajador = $trabajador->trabajador_tipo_trabajador;
        $usuario = $trabajador_tipo_trabajador->usuario;
        $nombres = $trabajador->trabajador_apellido . ' ' . $trabajador->trabajador_nombre;
        $usuario_correo = $usuario->usuario_correo;
        $usuario_contrasenia = $trabajador->trabajador_numero_documento;
        $correo = $trabajador->trabajador_correo;
        $modo = $this->value == 'create' ? 1 : 2;

        $data = [
            'usuario_correo' => $usuario_correo,
            'usuario_contrasenia' => $usuario_contrasenia,
            'nombres' => $nombres,
            'correo' => $correo,
            'modo' => $modo,
        ];

        Mail::send('modulo-coordinador.gestion-docentes.email-credenciales', $data, function ($message) use ($data) {
            $message->to($data['correo'])
                    ->subject('Credenciales de acceso - Escuela de Posgrado');
        });
    }
}
