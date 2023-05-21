@extends('layouts.modulo-administrador')
@section('title', 'Modulo Admitido - Escuela de Posgrado')
@section('content')
@livewire('modulo-administrador.gestion-admision.admitidos.index')
@endsection
@section('javascript')
    <script>
        // Evento para cerrar o abrir modal
        window.addEventListener('modalUsuario', event => {
            $(event.detail.titleModal).modal('hide');
        })
        // Alerta para confirmacion
        window.addEventListener('alerta_admitido', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: event.detail.confirmButtonText,
                customClass: {
                    confirmButton: "btn btn-"+event.detail.color,
                }
            });
        });
        //Alerta de cuestioranio para confirmar una accion
        window.addEventListener('alerta_generar_codigo', event => {
            // alert('Name updated to: ' + event.detail.id);
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                showCancelButton: true,
                confirmButtonText: event.detail.confirmButtonText,
                cancelButtonText: event.detail.cancelButtonText,
                customClass: {
                    confirmButton: "btn btn-"+event.detail.confirmButtonColor+" hover-elevate-up",//Hover y color del boton Confirmar
                    cancelButton: "btn btn-"+event.detail.cancelButtonColor+" hover-elevate-up",//Hover y color del boton Confirmar
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('modulo-administrador.gestion-admision.admitidos.index', 'generar_codigo');
                }
            });
        });

    </script>
@endsection
