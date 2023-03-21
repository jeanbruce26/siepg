@extends('layouts.modulo-plataforma')
@section('title', 'Pagos - Plataforma Escuela de Posgrado')
@section('content')
@livewire('modulo-plataforma.pagos.index')
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_pagos', event => {
        $('#modal_pagos').modal(event.detail.action);
    })
    window.addEventListener('alerta-pagos', event => {
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
