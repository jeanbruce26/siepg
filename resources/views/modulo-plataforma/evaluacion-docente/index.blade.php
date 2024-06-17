@extends('layouts.modulo-plataforma')
@section('title', 'Evaluacion_docente - Plataforma Escuela de Posgrado')
@section('content')
    @livewire('modulo-plataforma.evaluacion-docente.index')
@endsection
@section('scripts')
    <script>
        window.addEventListener('modal', event => {
            $(event.detail.modal).modal(event.detail.action);
        })
        window.addEventListener('alerta', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                buttonsStyling: false,
                confirmButtonText: event.detail.confirmButtonText,
                customClass: {
                    confirmButton: "btn btn-" + event.detail.color,
                }
            });
        })
    </script>
@endsection
