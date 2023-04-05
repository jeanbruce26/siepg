@extends('layouts.modulo-coordinador')
@section('title', 'Programas - Direccitor de Unidad - Escuela de Posgrado')
@section('content')
@livewire('modulo-coordinador.inicio.programas', ['id_modalidad' => $id_modalidad], key('modulo-coordinador.inicio.programas'))
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
