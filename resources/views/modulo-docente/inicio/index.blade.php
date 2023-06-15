@extends('layouts.modulo-docente')
@section('title', 'Docente de Posgrado - Escuela de Posgrado')
@section('content')
@livewire('modulo-docente.inicio.index')
@endsection
@section('scripts')
    <script>
        window.addEventListener('modal_encuesta', event => {
            $('#modal_encuesta').modal(event.detail.action);
        })
        window.addEventListener('alerta-encuesta', event => {
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
    </script>
@endsection
