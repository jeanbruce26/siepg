@extends('layouts.modulo-plataforma')
@section('title', 'Record Académico - Plataforma Escuela de Posgrado')
@section('content')
@livewire('modulo-plataforma.record-academico.index')
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_pago_plataforma', event => {
        $('#modal_pago_plataforma').modal(event.detail.action);
    })
    window.addEventListener('alerta_pago_plataforma', event => {
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
    window.addEventListener('alerta_pago_plataforma_2', event => {
        // alert('Name updated to: ' + event.detail.id);
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            showCancelButton: true,
            confirmButtonText: event.detail.confirmButtonText,
            cancelButtonText: event.detail.cancelButtonText,
            // confirmButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
            // cancelButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
            customClass: {
                confirmButton: "btn btn-"+event.detail.confirmButtonColor,
                cancelButton: "btn btn-"+event.detail.cancelButtonColor,
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('modulo-plataforma.pagos.index', 'guardar_pago');
            }
        });
    });
</script>
@endsection
