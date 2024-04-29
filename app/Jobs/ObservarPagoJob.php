<?php

namespace App\Jobs;

use App\Models\Pago;
use App\Models\PagoObservacion;
use App\Models\Persona;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ObservarPagoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id_pago;

    /**
     * Create a new job instance.
     */
    public function __construct($id_pago)
    {
        $this->id_pago = $id_pago;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pago = Pago::find($this->id_pago);
        $persona = Persona::find($pago->id_persona);
        $correo = $persona->correo;
        $nombre = $persona->nombre_completo;

        // verificar si tiene observacion
        $observacion = PagoObservacion::where('id_pago', $pago->id_pago)
            ->where('pago_observacion_estado', 1)
            ->orderBy('id_pago_observacion', 'desc')
            ->first()->pago_observacion;

        if ($pago->id_concepto_pago == 1) {
            $concepto_pago = 'Inscripción';
        } elseif ($pago->id_concepto_pago == 2) {
            $concepto_pago = 'Constancia de Ingreso';
        } elseif ($pago->id_concepto_pago == 3) {
            $concepto_pago = 'Matricula';
        } elseif ($pago->id_concepto_pago == 4) {
            $concepto_pago = 'Constancia de Ingreso y Matricula';
        } elseif ($pago->id_concepto_pago == 5) {
            $concepto_pago = 'Matricula Extemporanea';
        } elseif ($pago->id_concepto_pago == 6) {
            $concepto_pago = 'Constancia de Ingreso y Matricula Mxtemporanea';
        } elseif ($pago->id_concepto_pago == 7) {
            $concepto_pago = 'Costo por Enseñanza';
        } elseif ($pago->id_concepto_pago == 8) {
            $concepto_pago = 'Inscripcion de Traslado Externo';
        } else {
            $concepto_pago = 'Otros conceptos';
        }
        // datos del correo
        $detalle = [
            'correo' => $correo,
            'nombre' => $nombre,
            'observacion' => $observacion,
            'concepto_pago' => $concepto_pago
        ];

        Mail::send('components.email.observar-pago-conceptos', $detalle, function ($message) use ($detalle) {
            $message->to($detalle['correo'])
                ->subject('Pago Observado - Escuela de Posgrado');
        });
    }
}
