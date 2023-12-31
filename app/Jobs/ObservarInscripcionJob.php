<?php

namespace App\Jobs;

use App\Models\ExpedienteInscripcion;
use App\Models\Inscripcion;
use App\Models\PagoObservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ObservarInscripcionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id_inscripcion;
    public $tipo;

    /**
     * Create a new job instance.
     */
    public function __construct($id_inscripcion, $tipo)
    {
        $this->id_inscripcion = $id_inscripcion;
        $this->tipo = $tipo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $persona = $inscripcion->persona;
        $correo = $persona->correo;
        $nombre = $persona->nombre_completo;

        if ($this->tipo == 'observar-expediente') {
            $expedientes = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->get();

            // datos del correo
            $detalle = [
                'correo' => $correo,
                'nombre' => $nombre,
                'expedientes' => $expedientes,
            ];

            Mail::send('components.email.observar-expedientes', $detalle, function ($message) use ($detalle) {
                $message->to($detalle['correo'])
                    ->subject('Expedientes Observados - Escuela de Posgrado');
            });
        } else if ($this->tipo == 'observar-pago') {
            $pago = $inscripcion->pago;
            // verificar si tiene observacion
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)
                ->where('pago_observacion_estado', 1)
                ->orderBy('id_pago_observacion', 'desc')
                ->first();

            // datos del correo
            $detalle = [
                'correo' => $correo,
                'nombre' => $nombre,
                'pago' => $pago,
                'observacion' => $observacion,
            ];

            Mail::send('components.email.observar-pago', $detalle, function ($message) use ($detalle) {
                $message->to($detalle['correo'])
                    ->subject('Pago Observado - Escuela de Posgrado');
            });
        }
    }
}
