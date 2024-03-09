<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarCorreos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $asunto;
    public $mensaje;
    public $correos;

    /**
     * Create a new job instance.
     */
    public function __construct($asunto, $mensaje, $correos)
    {
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->correos = $correos;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $asunto = $this->asunto;
        $mensaje = $this->mensaje;

        foreach ($this->correos as $correo) {
            $data = [
                'asunto' => $asunto,
                'mensaje' => $mensaje,
                'correo' => $correo,
            ];
            Mail::send('components.email.base-correo', $data, function ($message) use ($data) {
                $message->to($data['correo'])
                    ->subject($data['asunto']);
            });
        }
    }
}
