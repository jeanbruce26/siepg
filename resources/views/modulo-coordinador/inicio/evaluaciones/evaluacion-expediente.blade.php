@extends('layouts.modulo-coordinador')
@section('title', 'Evaluacion de Expediente - Direccitor de Unidad - Escuela de Posgrado')
@section('content')
@livewire('modulo-coordinador.inicio.evaluaciones.evaluacion-expediente', ['id_programa' => $id_programa, 'id_admision' => $id_admision, 'id_evaluacion' => $id_evaluacion], key('modulo-coordinador.inicio.evaluacion-expediente'))
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
