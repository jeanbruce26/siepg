@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.gestion-curricular.admision.index')

@endsection

@section('javascript')
<script>
    window.addEventListener('modal', event => {   
        $(event.detail.titleModal).modal('hide');
    })

    // Alerta para confirmacion
	window.addEventListener('alerta-admision', event => {
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

    //alerta 
    window.addEventListener('alertaConfirmacion', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            showCancelButton: true,
            confirmButtonText: event.detail.confirmButtonText,
            cancelButtonText: event.detail.cancelButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.confimrColor+" hover-elevate-up", //Hover y color del boton Confirmar
                cancelButton: "btn btn-"+event.detail.cancelColor+" hover-elevate-up", //Hover y color del boton Cancel
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('modulo-administrador.gestion-curricular.admision.index', event.detail.metodo, event.detail.id);
            }
        })
    })
</script>
@endsection