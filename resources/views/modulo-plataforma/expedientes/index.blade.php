@extends('layouts.modulo-plataforma')
@section('title', 'Expedientes - Plataforma Escuela de Posgrado')
@section('content')
@livewire('modulo-plataforma.expedientes.index')
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_expediente', event => {
        $('#modal_expediente').modal(event.detail.action);
    })
    window.addEventListener('alerta-expedientes', event => {
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
