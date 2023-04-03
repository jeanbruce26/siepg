@extends('layouts.modulo-area-contable')
@section('title', 'Pagos - Area Contable - Escuela de Posgrado')
@section('content')
@livewire('modulo-area-contable.pagos.index')
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_pago_contable', event => {
        $('#modal_pago_contable').modal(event.detail.action);
    })
    window.addEventListener('alerta_pago_contable', event => {
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
