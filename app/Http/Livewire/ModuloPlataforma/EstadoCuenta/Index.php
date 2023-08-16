<?php

namespace App\Http\Livewire\ModuloPlataforma\EstadoCuenta;

use App\Models\Admitido;
use App\Models\AdmitidoCiclo;
use App\Models\CostoEnseñanza;
use App\Models\Mensualidad;
use App\Models\Persona;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $usuario;
    public $persona;
    public $admitido;

    // opciones de busqueda
    public $search = '';

    // opciones de filtro
    public $filtro_ciclo;
    public $ciclo_data;
    public $admitido_ciclo;
    public $costo_enseñanza;

    protected $queryString = [
        'filtro_ciclo' => ['except' => ''],
        'ciclo_data' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->usuario = auth('plataforma')->user();
        $this->persona = Persona::where('id_persona', $this->usuario->id_persona)->first();
        $this->admitido = Admitido::where('id_persona', $this->persona->id_persona)->orderBy('id_admitido', 'desc')->first();
        if ( $this->admitido == null )
        {
            abort(403);
        }
        $this->admitido_ciclo = AdmitidoCiclo::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_admitido_ciclo', 'desc')->first();
        if ( $this->admitido_ciclo == null )
        {
            abort(403);
        }
        $this->filtro_ciclo = $this->admitido_ciclo->id_ciclo;
        $this->ciclo_data = $this->admitido_ciclo->id_ciclo;
        $this->costo_enseñanza = CostoEnseñanza::where('id_programa_plan', $this->admitido->programa_proceso->id_programa_plan)->where('id_ciclo', $this->admitido_ciclo->id_ciclo)->first();
    }

    public function aplicar_filtro()
    {
        $this->ciclo_data = $this->filtro_ciclo;
        $this->costo_enseñanza = CostoEnseñanza::where('id_programa_plan', $this->admitido->programa_proceso->id_programa_plan)->where('id_ciclo', $this->ciclo_data)->first();
    }

    public function resetear_filtro()
    {
        $this->reset([
            'filtro_ciclo',
            'ciclo_data'
        ]);
        $this->admitido_ciclo = AdmitidoCiclo::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_admitido_ciclo', 'desc')->first();
        $this->filtro_ciclo = $this->admitido_ciclo->id_ciclo;
        $this->ciclo_data = $this->admitido_ciclo->id_ciclo;
    }

    public function render()
    {
        $this->admitido_ciclo = AdmitidoCiclo::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_admitido_ciclo', 'desc')->first();

        $mensualidades  = Mensualidad::join('matricula', 'mensualidad.id_matricula', '=', 'matricula.id_matricula')
                                        ->join('pago', 'mensualidad.id_pago', '=', 'pago.id_pago')
                                        ->where('mensualidad.id_admitido', $this->admitido->id_admitido)
                                        ->where('matricula.id_ciclo', $this->ciclo_data ? '=' : '!=', $this->ciclo_data)
                                        ->where(function ($query) {
                                            $query->where('pago.pago_operacion', 'like', "%{$this->search}%")
                                                ->orWhere('mensualidad.id_mensualidad', 'like', "%{$this->search}%");
                                        })
                                        ->orderBy('mensualidad.id_mensualidad', 'asc')
                                        ->paginate(5);

        $monto_total = $this->costo_enseñanza->costo_ciclo;
        $monto_pagado = 0;

        foreach($mensualidades as $mensualidad)
        {
            if ( $mensualidad->pago->pago_estado == 2 && $mensualidad->pago->pago_verificacion == 2 )
            {
                $monto_pagado += $mensualidad->pago->pago_monto;
            }
        }

        $deuda = $monto_total - $monto_pagado;

        $ciclos = AdmitidoCiclo::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_admitido_ciclo', 'asc')->get();

        return view('livewire.modulo-plataforma.estado-cuenta.index', [
            'mensualidades' => $mensualidades,
            'monto_total' => $monto_total,
            'monto_pagado' => $monto_pagado,
            'deuda' => $deuda,
            'ciclos' => $ciclos,
        ]);
    }
}
