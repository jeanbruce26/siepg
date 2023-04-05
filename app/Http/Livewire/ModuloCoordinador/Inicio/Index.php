<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio;

use App\Models\Modalidad;
use Livewire\Component;

class Index extends Component
{
    public $modalidades; // variable para almacenar la modalidad del coordinador

    public function mount()
    {
        $this->modalidades = Modalidad::where('modalidad_estado', 1)->get();
    }

    public function ingresar(Modalidad $modalidad)
    {
        return redirect()->route('coordinador.programas', $modalidad->id_modalidad);
    }

    public function render()
    {
        return view('livewire.modulo-coordinador.inicio.index');
    }
}
