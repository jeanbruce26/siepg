@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.gestion-usuarios.trabajador.index')

@endsection

@section('javascript')
<script>

    window.addEventListener('modal', event => {   
        $(event.detail.titleModal).modal('hide');
    })

    // Alerta para confirmacion
	window.addEventListener('alerta-trabajador', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            buttonsStyling: false,
			confirmButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color, // Color del boton de confirmación
            }
        });
    });

    //alerta cambiar estado trabajador y desasignar trabajador
    window.addEventListener('alertaConfirmacion', event => {
        // alert('Name updated to: ' + event.detail.id);
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            showCancelButton: true,
            confirmButtonText: event.detail.confirmButtonText,
            cancelButtonText: event.detail.cancelButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.confimrColor+" hover-elevate-up",
                cancelButton: "btn btn-"+event.detail.cancelColor+" hover-elevate-up",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('modulo-administrador.usuario.trabajador', event.detail.metodo, event.detail.id);
            }
        })
    })
</script>
@endsection