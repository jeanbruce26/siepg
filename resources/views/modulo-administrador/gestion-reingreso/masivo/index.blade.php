@extends('layouts.modulo-administrador')
@section('title', 'Gestión de Reingreso Masivo - Escuela de Posgrado')
@section('content')
@livewire('modulo-administrador.gestion-reingreso.masivo.index')
@endsection
@section('scripts')
    <script>
        window.addEventListener('modal', event => {
            $(event.detail.modal).modal(event.detail.action);
        })
        window.addEventListener('alerta-basica', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                buttonsStyling: false,
                confirmButtonText: event.detail.confirmButtonText,
                customClass: {
                    confirmButton: "btn btn-"+event.detail.color,
                }
            });
        })
        window.addEventListener('alerta-avanzada', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                showCancelButton: true,
                confirmButtonText: event.detail.confirmButtonText,
                cancelButtonText: event.detail.cancelButtonText,
                customClass: {
                    confirmButton: "btn btn-"+event.detail.confirmButtonColor,
                    cancelButton: "btn btn-"+event.detail.cancelButtonColor,
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('modulo-coordinador.gestion-docentes.index', event.detail.function);
                }
            });
        });
    </script>
@endsection
