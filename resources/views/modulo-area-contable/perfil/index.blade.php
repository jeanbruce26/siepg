@extends('layouts.modulo-area-contable')
@section('title', 'Perfil - Area Contable - Escuela de Posgrado')
@section('content')
@livewire('modulo-administrador.perfil.index', ['id_tipo_trabajador' => $id_tipo_trabajador], key('modulo-administrador.perfil.index'))
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_pago_contable', event => {
        $('#modal_pago_contable').modal(event.detail.action);
    })
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
