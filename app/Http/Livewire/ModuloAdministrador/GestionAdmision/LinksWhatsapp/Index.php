<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\LinksWhatsapp;

use App\Models\Admision;
use App\Models\LinkWhatsapp;
use App\Models\Modalidad;
use App\Models\ProgramaPlan;
use App\Models\ProgramaProceso;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // Trait de Paginacion
    protected $paginationTheme = 'bootstrap'; // Tema de la Paginacion

    public $search = ''; // Variable de Busqueda

    // variables para el formulario modal
    public $titulo = 'Crear Nuevo Link de WhatsApp';
    public $modo = 'create';
    public $id_link_whatsapp;
    public $proceso;
    public $modalidad;
    public $programa_academico;
    public $link;

    // varianbles de filtro
    public $proceso_filtro;
    public $data_proceso_filtro;
    public $modalidad_filtro;
    public $data_modalidad_filtro;

    protected $queryString = [
        'search' => ['except' => ''], // Variable de Busqueda
        'proceso_filtro' => ['except' => '', 'as' => 'pf'], // Variable de Filtro
        'data_proceso_filtro' => ['except' => '', 'as' => 'dpf'], // Variable de Filtro
        'modalidad_filtro' => ['except' => '', 'as' => 'mf'], // Variable de Filtro
        'data_modalidad_filtro' => ['except' => '', 'as' => 'dmf'] // Variable de Filtro
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'proceso' => 'required',
            'modalidad' => 'required',
            'programa_academico' => 'required',
            'link' => 'required|url'
        ]);
    }

    public function resetear_filtro()
    {
        $this->reset([
            'proceso_filtro',
            'data_proceso_filtro',
            'modalidad_filtro',
            'data_modalidad_filtro'
        ]);
    }

    public function filtrar()
    {
        $this->data_proceso_filtro = $this->proceso_filtro;
        $this->data_modalidad_filtro = $this->modalidad_filtro;
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->titulo = 'Crear Nuevo Link de WhatsApp';
        $this->modo = 'create';
    }

    public function limpiar_modal()
    {
        $this->reset([
            'proceso',
            'modalidad',
            'programa_academico',
            'link'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedProceso()
    {
        $this->programa_academico = null;
    }

    public function updatedModalidad()
    {
        $this->programa_academico = null;
    }

    public function cargar_link($id_link_whatsapp)
    {
        $link = LinkWhatsapp::find($id_link_whatsapp);
        $this->id_link_whatsapp = $link->id_link_whatsapp;
        $this->titulo = 'Editar Link de WhatsApp';
        $this->modo = 'edit';
        $this->proceso = $link->id_admision;
        $this->modalidad = $link->programa_proceso->programa_plan->programa->id_modalidad;
        $this->programa_academico = $link->id_programa_proceso;
        $this->link = $link->link_whatsapp;
    }

    public function guardar_link()
    {
        $this->validate([
            'proceso' => 'required',
            'modalidad' => 'required',
            'programa_academico' => 'required',
            'link' => 'required|url'
        ]);

        if (
            $this->modo == 'create'
        ) {
            // validar si el link se registrar en el mismo proceso y programa
            $link = LinkWhatsapp::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'link_whatsapp_programas.id_programa_proceso')
                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                        ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                        ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
                        ->join('admision', 'admision.id_admision', '=', 'link_whatsapp_programas.id_admision')
                        ->where('programa_proceso.id_admision', '=', $this->proceso)
                        ->where('programa.id_modalidad', '=', $this->modalidad)
                        ->where('programa_proceso.id_programa_proceso', '=', $this->programa_academico)
                        ->first();
            if ($link) {
                $this->dispatchBrowserEvent('alerta-basica', [
                    'title' => 'Registro Fallido',
                    'text' => 'El Link de WhatsApp ya se encuentra registrado para el proceso y programa seleccionado.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }

            $link = new LinkWhatsapp();
            $link->link_whatsapp = $this->link;
            $link->id_programa_proceso = $this->programa_academico;
            $link->id_admision = $this->proceso;
            $link->link_whatsapp_fecha_creacion = date('Y-m-d H:i:s');
            $link->link_whatsapp_estado = 1;
            $link->save();
        } elseif (
            $this->modo == 'edit'
        ) {
            $link = LinkWhatsapp::find($this->id_link_whatsapp);
            $link->link_whatsapp = $this->link;
            $link->id_programa_proceso = $this->programa_academico;
            $link->id_admision = $this->proceso;
            $link->save();
        }

        // evento para cerrar el modal
        $this->dispatchBrowserEvent('modal', ['modal' => '#modal_links_whatsapp', 'action' => 'hide']);

        // mostrar mensaje de registro exitoso o de actualizacion exitosa
        if (
            $this->modo == 'create'
        ) {
            $this->dispatchBrowserEvent('alerta-basica', [
                'title' => 'Registro Exitoso',
                'text' => 'El Link de WhatsApp se registró satisfactoriamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        } elseif (
            $this->modo == 'edit'
        ) {
            $this->dispatchBrowserEvent('alerta-basica', [
                'title' => 'Actualización Exitosa',
                'text' => 'El Link de WhatsApp se actualizó satisfactoriamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
    }

    public function cargarAlerta(LinkWhatsapp $link)
    {
        if (
            $link->link_whatsapp_estado == 1
        ) {
            $link->link_whatsapp_estado = 0;
        } elseif (
            $link->link_whatsapp_estado == 0
        ) {
            $link->link_whatsapp_estado = 1;
        }
        $link->save();

        // mostrar mensaje de cambio de estado exitoso
        $this->dispatchBrowserEvent('alerta-basica', [
            'title' => '¡Exito!',
            'text' => 'El estado del Link de WhatsApp se actualizó satisfactoriamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function render()
    {
        $links = LinkWhatsapp::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'link_whatsapp_programas.id_programa_proceso')
                    ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                    ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
                    ->join('admision', 'admision.id_admision', '=', 'link_whatsapp_programas.id_admision')
                    ->where(function ($query) {
                        $query->where('programa.mencion', 'like', '%' . $this->search . '%')
                            ->orWhere('programa.subprograma', 'like', '%' . $this->search . '%')
                            ->orWhere('programa.programa', 'like', '%' . $this->search . '%')
                            ->orWhere('admision.admision', 'like', '%' . $this->search . '%')
                            ->orWhere('modalidad.modalidad', 'like', '%' . $this->search . '%');
                    })
                    ->where('programa_proceso.id_admision', $this->data_proceso_filtro == null ? '!=' : '=', $this->data_proceso_filtro)
                    ->where('programa.id_modalidad', $this->data_modalidad_filtro == null ? '!=' : '=', $this->data_modalidad_filtro)
                    ->orderBy('link_whatsapp_programas.id_link_whatsapp', 'desc')
                    ->paginate(10);

        // modelos para el formulario modal
        $admisiones = Admision::orderBy('admision_año', 'desc')
                        ->get(); // Modelo de Admisiones

        $modalidades = Modalidad::orderBy('modalidad', 'asc')
                        ->get(); // Modelo de Modalidades

        if ($this->proceso && $this->modalidad) {
            $programas = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                            ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
                            ->join('admision', 'admision.id_admision', '=', 'programa_proceso.id_admision')
                            ->where('programa_proceso.id_admision', '=', $this->proceso)
                            ->where('programa.id_modalidad', '=', $this->modalidad)
                            ->get(); // Modelo de Programas
        } else {
            $programas = collect(); // Modelo de Programas
        }

        return view('livewire.modulo-administrador.gestion-admision.links-whatsapp.index', [
            'links' => $links,
            'admisiones' => $admisiones,
            'modalidades' => $modalidades,
            'programas' => $programas
        ]);
    }
}
