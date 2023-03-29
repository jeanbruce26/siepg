@extends('layouts.modulo-plataforma')
@section('title', 'Pagos - Plataforma Escuela de Posgrado')
@section('content')
@livewire('modulo-plataforma.pagos.index')
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
</script>
@endsection
