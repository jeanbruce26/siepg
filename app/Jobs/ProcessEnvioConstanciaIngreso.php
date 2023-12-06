<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ProcessEnvioConstanciaIngreso implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $path, $filename, $nombre, $correo;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $path, $filename, $nombre, $correo)
    {
        $this->data = $data;
        $this->path = $path;
        $this->filename = $filename;
        $this->nombre = $nombre;
        $this->correo = $correo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // generar pdf
        $nombre_pdf = $this->filename;
        $pdf = PDF::loadView('modulo-plataforma.constancia-ingreso.ficha-constancia-ingreso', $this->data);
        $pdf_email = $pdf->output();

        // datos del correo
        $detalle = [
            'correo' => $this->correo,
            'nombre' => $this->nombre
        ];

        Mail::send('modulo-plataforma.constancia-ingreso.email', $detalle, function ($message) use ($detalle, $pdf_email, $nombre_pdf) {
            $message->to($detalle['correo'])
                    ->subject('Constancia de Ingreso - Escuela de Posgrado')
                    ->attachData($pdf_email, $nombre_pdf, ['mime' => 'application/pdf']);
        });
    }
}
