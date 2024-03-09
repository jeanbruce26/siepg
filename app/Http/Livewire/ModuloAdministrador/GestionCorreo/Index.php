<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionCorreo;

use App\Models\Correo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
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
