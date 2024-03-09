<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionCorreo;

use App\Models\Correo;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public Collection $correos_enviados;
    public $asunto;

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function mount()
    {
        $this->correos_enviados = collect();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function cargar_correos($id_correo)
    {
        $correo = Correo::find($id_correo);
        $this->asunto = $correo->correo_asunto;
        $correo = json_decode($correo->correo_enviados);
        $this->correos_enviados = collect($correo);
    }

    public function render()
    {
        $correos = Correo::search($this->search)
            ->orderBy('id_correo', 'desc')
            ->paginate(10);

        return view('livewire.modulo-administrador.gestion-correo.index', [
            'correos' => $correos
        ]);
    }
}
