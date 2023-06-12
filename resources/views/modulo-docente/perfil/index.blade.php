@extends('layouts.modulo-docente')
@section('title', 'Perfil - Docente de la Escuela de Posgrado - Escuela de Posgrado')
@section('content')
@livewire('modulo-administrador.perfil.index', ['id_tipo_trabajador' => $id_tipo_trabajador], key('modulo-administrador.perfil.index'))
@endsection
@section('scripts')
<script>
    window.addEventListener('alerta_perfil', event => {
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
