<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio;

use App\Models\Admision;
use App\Models\Coordinador;
use App\Models\Modalidad;
use App\Models\Programa;
use App\Models\ProgramaProceso;
use Livewire\Component;

class Programas extends Component
{
    public $admisiones, $admision; // variable para almacenar las admisiones
    public $filtro_proceso; // variable para filtrar por proceso de admision
    public $proceso; // variable para almacenar el proceso de admision
    public $facultad; // variable para almacenar la facultad del coordinador
    public $programas; // variable para almacenar los programas de la facultad del coordinador
    public $modalidad, $id_modalidad; // variable para almacenar la modalidad del coordinador

    public function mount()
    {
        $this->modalidad = Modalidad::find($this->id_modalidad);
        if(!$this->modalidad)
        {
            abort(404);
        }
        $this->admisiones = Admision::all();
        $admision = Admision::where('admision_estado', 1)->first();
        $this->filtro_proceso = $admision->id_admision;
        $this->proceso = $this->filtro_proceso;
        $this->admision = Admision::where('id_admision', $this->filtro_proceso)->first();
        $this->facultad = Coordinador::where('id_trabajador',auth('usuario')->user()->trabajador_tipo_trabajador->id_trabajador)->first();
        $this->programas = Programa::where('id_facultad',$this->facultad->facultad->id_facultad)
                                    ->where('programa_estado',1)
                                    ->where('id_modalidad',$this->id_modalidad)
                                    ->get();
    }

    public function aplicar_filtro()
    {
        if($this->filtro_proceso == null || $this->filtro_proceso == "")
        {
            $this->mount();
        }
        else
        {
            $this->proceso = $this->filtro_proceso;
        }
    }

    public function resetear_filtro()
    {
        $this->mount();
    }

    public function render()
    {
        return view('livewire.modulo-coordinador.inicio.programas');
    }
}
