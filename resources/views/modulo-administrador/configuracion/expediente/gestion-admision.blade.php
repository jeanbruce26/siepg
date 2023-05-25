@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.configuracion.expediente.gestion-admision', ['id_expediente' => $id_expediente])

@endsection

@section('javascript')
<script>
    window.addEventListener('modal', event => {   
        $(event.detail.titleModal).modal('hide');
    })

    // Alerta para confirmacion
	window.addEventListener('alerta-expediente-gestion-admision', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            buttonsStyling: false,
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color+" hover-elevate-up", //Hover y color del boton Confirmar
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
                Livewire.emitTo('modulo-administrador.configuracion.expediente.gestion-admision', event.detail.metodo, event.detail.id);
            }
        })
    })
</script>
@endsection