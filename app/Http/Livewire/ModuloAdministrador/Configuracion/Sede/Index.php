<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Sede;

use App\Models\Sede;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap
    
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $titulo = 'Crear Sede';
    public $id_sede;

    public $modo = 1;

    public $sede;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'sede' => 'required|string'
        ]);
    }

    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Crear Sede';
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('sede');
        $this->modo = 1;
    }

    public function alertaConfirmacion($title, $text, $icon, $confirmButtonText, $cancelButtonText, $confimrColor, $cancelColor, $metodo, $id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'cancelButtonText' => $cancelButtonText,
            'confimrColor' => $confimrColor,
            'cancelColor' => $cancelColor,
            'metodo' => $metodo,
            'id' => $id,
        ]);
    }

    public function alertaSede($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-sede', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cargarAlertaEstado(Sede $sede)
    {
        if ($sede->sede_estado == 1) {
            $this->alertaConfirmacion('¿Estás seguro?', '¿Desea desactivar el estado de la sede ' . $sede->sede . '?', 'question', 'Modificar', 'Cancelar', 'primary', 'danger', 'cambiarEstado', $sede->id_sede);

        } else {
            $this->alertaConfirmacion('¿Estás seguro?', '¿Desea activar el estado de la sede ' . $sede->sede . '?', 'question', 'Modificar', 'Cancelar', 'primary', 'danger', 'cambiarEstado', $sede->id_sede);
        }
    }

    public function cambiarEstado(Sede $sede)
    {
        if ($sede->sede_estado == 1) {
            $sede->sede_estado = 0;
            $sede->save();

            $this->alertaSede('¡Éxito!', 'La sede ' . $sede->sede . ' ha sido desactivada satisfactoriamente.', 'success', 'Aceptar', 'success');
        } else {
            $sede->sede_estado = 1;
            $sede->save();
            $this->alertaSede('¡Éxito!', 'La sede ' . $sede->sede . ' ha sido activada satisfactoriamente.', 'success', 'Aceptar', 'success');
        }
    }

    public function cargarSede(Sede $sede)
    {
        $this->limpiar();
        $this->id_sede = $sede->id_sede;
        $this->sede = $sede->sede;
        $this->modo = 2;
        $this->titulo = 'Editar Sede';
    }

    public function guardarSede()
    {
        if ($this->modo == 1) {
            $this->validate([
                'sede' => 'required|string|unique:sede,sede'    
            ]);

            $sede = new Sede();
            $sede->sede = $this->sede;
            $sede->save();

            $this->alertaSede('¡Éxito!', 'La sede ' . $sede->sede . ' ha sido creada satisfactoriamente.', 'success', 'Aceptar', 'success');

        } else {
            $this->validate([
                'sede' => 'required|string|unique:sede,sede,' . $this->id_sede . ',id_sede'
            ]);
            
            $sede = Sede::find($this->id_sede);

            if($sede->sede == $this->sede){
                $this->alertaSede('¡Información!', 'No se realizaron cambios en los datos de la sede.', 'info', 'Aceptar', 'info');

            }else{
                $sede->sede = $this->sede;
                $sede->save();
                
                $this->alertaSede('¡Éxito!', 'La sede ' . $sede->sede . ' ha sido actualizada satisfactoriamente.', 'success', 'Aceptar', 'success');
            }
        }

        $this->limpiar();
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalSede',
        ]);
    }

    public function render()
    {
        $sede_model = Sede::where('sede', 'like', '%' . $this->search . '%')
            ->orderBy('sede', 'desc')
            ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.sede.index',[
            'sede_model' => $sede_model
        ]);
    }

}
