<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio;

use App\Models\Admision;
use App\Models\Inscripcion;
use App\Models\Programa;
use Livewire\Component;

class Inscripciones extends Component
{
    public $id_programa; // es el id del programa que se esta consultando
    public $programa; // es el programa que se esta consultando
    public $id_admision; // es el id de la admision que se esta consultando
    public $admision; // es la admision que se esta consultando
    public $inscripciones; // es el listado de inscripciones del programa

    public $search = ''; // Variable para la busqueda
    public $sort_nombre = 'nombre_completo'; // Columna de la tabla a ordenar
    public $sort_direccion = 'asc'; // Orden de la columna a ordenar

    protected $queryString = [
        'search' => ['except' => ''],
        'sort_nombre' => ['except' => 'nombre_completo'],
        'sort_direccion' => ['except' => 'asc'],
    ];

    public function mount()
    {
        $this->programa = Programa::find($this->id_programa);
        $this->admision = Admision::find($this->id_admision);
    }

    public function ordenar_tabla($value)
    {
        if ($this->sort_nombre == $value) {
            if ($this->sort_direccion == 'asc') {
                $this->sort_direccion = 'desc';
            } else {
                $this->sort_direccion = 'asc';
            }
        } else {
            $this->sort_nombre = $value;
            $this->sort_direccion = 'asc';
        }
    }

    public function render()
    {
        $this->inscripciones = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                        ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                                        ->where('programa.id_programa', $this->id_programa)
                                        ->where('programa_proceso.id_admision', $this->id_admision)
                                        ->where(function($query) {
                                            $query->where('persona.nombre_completo', 'like', '%' . $this->search . '%')
                                                ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%');
                                        })
                                        ->orderBy($this->sort_nombre == 'nombre_completo' ? 'persona.' . $this->sort_nombre :'inscripcion.' .  $this->sort_nombre, $this->sort_direccion)
                                        ->get();
        return view('livewire.modulo-coordinador.inicio.inscripciones');
    }
}
