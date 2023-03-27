@extends('layouts.modulo-area-contable')
@section('title', 'Area Contable - Escuela de Posgrado')
@section('content')
@livewire('modulo-area-contable.inicio.index')
@endsection
@section('scripts')
{{-- <script>
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
</script> --}}
@endsection
