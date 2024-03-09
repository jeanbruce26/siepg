<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionCorreo;

use App\Jobs\EnviarCorreos;
use App\Models\Admision;
use App\Models\Correo;
use App\Models\Modalidad;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\ProgramaProceso;
use Illuminate\Support\Collection;
use Livewire\Component;

class Create extends Component
{
    public $tipo_envio;
    public $buscar_dni;
    public $persona;
    public $tipo_envio_tabla;
    public $proceso;
    public $modalidad;
    public Collection $modalidades;
    public $programa;
    public Collection $programas;
    public $cantidad_correos = 0;

    public $asunto;
    public $mensaje;

    public function mount()
    {
        $this->modalidades = collect();
        $this->programas = collect();
        $this->cantidad_correos = 0;
    }

    public function updatedTipoEnvio($value)
    {
        if ($value == 1) {
            $this->reset(['buscar_dni', 'persona']);
        } else {
            $this->tipo_envio_tabla = null;
            $this->reset(['proceso', 'modalidad', 'programa']);
            $this->modalidades = collect();
            $this->programas = collect();
        }

        $this->cantidad_correos = 0;
    }

    public function buscar_persona()
    {
        $this->validate([
            'buscar_dni' => 'required|numeric|digits:8'
        ]);

        $this->persona = Persona::where('numero_documento', $this->buscar_dni)->first();
    }

    public function updatedProceso($value)
    {
        if ($value != null) {
            $this->modalidades = Modalidad::where('modalidad_estado', 1)->get();
            $this->modalidad = null;
            $this->programas = collect();
        } else {
            $this->modalidades = collect();
            $this->modalidad = null;
            $this->programas = collect();
        }

        $this->cantidad_correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $value, null, null)['cantidad'];
    }

    public function updatedModalidad($value)
    {
        if ($value != null) {
            $this->programas = Programa::where('id_modalidad', $value)
                ->where('programa_estado', 1)
                ->get();
            $this->programa = null;
        } else {
            $this->programas = collect();
            $this->programa = null;
        }

        $this->cantidad_correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $value, null)['cantidad'];
    }

    public function updatedPrograma($value)
    {
        $this->cantidad_correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $this->modalidad, $value)['cantidad'];
    }

    public function enviar_correo()
    {
        $this->validate([
            'tipo_envio' => 'required',
            'asunto' => 'required',
            'mensaje' => 'required'
        ]);

        if ($this->tipo_envio == 1) {
            $this->validate([
                'buscar_dni' => 'required|numeric|digits:8'
            ]);

            $correos_db = json_encode([$this->persona->correo]);
            $correos = [$this->persona->correo];
        } else {
            $this->validate([
                'proceso' => 'required',
            ]);

            $correos_db = json_encode(calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $this->modalidad, $this->programa)['correos']);
            $correos = calcularCantidadDePersonas($this->tipo_envio_tabla, $this->proceso, $this->modalidad, $this->programa)['correos'];
        }

        $asunto = $this->asunto;
        $mensaje = $this->mensaje;
        $correo_json = $correos_db;

        $correo = new Correo();
        $correo->correo_asunto = $asunto;
        $correo->correo_mensaje = $mensaje;
        $correo->correo_enviados = $correo_json;
        $correo->correo_estado = 1;
        $correo->save();

        // ejecutar el envio de correos masivos o individuales con un job
        EnviarCorreos::dispatch($asunto, $mensaje, $correos);

        // redireccionar a la vista de correos
        return redirect()->route('administrador.gestion-correo');
    }

    public function render()
    {
        $procesos = Admision::orderBy('id_admision', 'desc')->get();

        return view('livewire.modulo-administrador.gestion-correo.create', [
            'procesos' => $procesos
        ]);
    }
}
