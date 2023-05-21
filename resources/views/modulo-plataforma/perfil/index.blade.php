@extends('layouts.modulo-plataforma')
@section('title', 'Perfil - Plataforma Escuela de Posgrado')
@section('content')
@livewire('modulo-plataforma.perfil.index')
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_perfil', event => {
        $('#modal_perfil').modal(event.detail.action);
    })
    window.addEventListener('update_perfil', event => {
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
