<?php

namespace App\Jobs;

use App\Models\Persona;
use App\Models\UsuarioEstudiante;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessEnvioCredenciales implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $id_persona;

    public function __construct($id_persona)
    {
        $this->id_persona = $id_persona;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //Enviar las credenciales al correo
        $persona = Persona::where('id_persona', $this->id_persona)->first();
        $nombres = $persona->nombre_completo;
        $usuario_estudiante = UsuarioEstudiante::where('id_persona', $this->id_persona)->first();
        $usuario = $usuario_estudiante->usuario_estudiante;
        $contraseña = $persona->numero_documento;

        $data = [
            'nombres' => $nombres,
            'usuario' => $usuario,
            'contraseña' => $contraseña
        ];

        Mail::send('modulo-inscripcion.registro-alumnos.email', $data, function ($message) use ($data) {
            $message->to($data['usuario'])
                    ->subject('Credenciales de acceso - Escuela de Posgrado');
        });
    }
}
