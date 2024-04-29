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
    public $id_concepto_pago;

    /**
     * Create a new job instance.
     */
    public function __construct($id_pago, $id_concepto_pago)
    {
        $this->id_pago = $id_pago;
        $this->id_concepto_pago = $id_concepto_pago;
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

        $concepto_pago = '';
        if ($this->id_concepto_pago === 1) {
            $concepto_pago = 'inscripción';
        } elseif ($this->id_concepto_pago === 2) {
            $concepto_pago = 'constancia de ingreso';
        } elseif ($this->id_concepto_pago === 3) {
            $concepto_pago = 'matricula';
        } elseif ($this->id_concepto_pago === 4) {
            $concepto_pago = 'constancia de ingreso y matricula';
        } elseif ($this->id_concepto_pago === 5) {
            $concepto_pago = 'matricula extemporanea';
        } elseif ($this->id_concepto_pago === 6) {
            $concepto_pago = 'constancia de ingreso y matricula extemporanea';
        } elseif ($this->id_concepto_pago === 7) {
            $concepto_pago = 'costo por enseñanza';
        } elseif ($this->id_concepto_pago === 8) {
            $concepto_pago = 'inscripcion de traslado externo';
        } else {
            $concepto_pago = 'otros conceptos';
        }

        // datos del correo
        $detalle = [
            'correo' => $correo,
            'nombre' => $nombre,
            'pago' => $pago,
            'observacion' => $observacion,
            'concepto_pago' => $concepto_pago,
        ];

        Mail::send('components.email.observar-pago', $detalle, function ($message) use ($detalle) {
            $message->to($detalle['correo'])
                ->subject('Pago Observado - Escuela de Posgrado');
        });
    }
}
