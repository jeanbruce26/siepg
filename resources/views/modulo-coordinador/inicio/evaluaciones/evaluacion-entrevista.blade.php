@extends('layouts.modulo-coordinador')
@section('title', 'Evaluacion de Entrevista - Direccitor de Unidad - Escuela de Posgrado')
@section('content')
@livewire('modulo-coordinador.inicio.evaluaciones.evaluacion-entrevista', ['id_programa' => $id_programa, 'id_admision' => $id_admision, 'id_evaluacion' => $id_evaluacion], key('modulo-coordinador.inicio.evaluacion-entrevista'))
@endsection
@section('scripts')
    <script>
        window.addEventListener('modal_puntaje', event => {
            $('#modal_puntaje').modal(event.detail.action);
        })
        window.addEventListener('alerta_evaluacion_entrevista', event => {
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
        });
        window.addEventListener('alerta_evaluacion_entrevista_2', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                showCancelButton: true,
                confirmButtonText: event.detail.confirmButtonText,
                cancelButtonText: event.detail.cancelButtonText,
                customClass: {
                    confirmButton: "btn btn-"+event.detail.colorConfirmButton,
                    cancelButton: "btn btn-"+event.detail.colorCancelButton,
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('modulo-coordinador.inicio.evaluaciones.evaluacion-entrevista', 'evaluar_entrevista_paso_2');
                }
            })
        })
    </script>
@endsection
