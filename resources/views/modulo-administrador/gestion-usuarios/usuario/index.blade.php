@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.gestion-usuarios.usuario.index')

@endsection

@section('javascript')
<script>
	window.addEventListener('modalUsuario', event => {
		$(event.detail.titleModal).modal('hide');
	})

	// Alerta para confirmacion
	window.addEventListener('alerta-usuario', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            buttonsStyling: false,
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color+" hover-elevate-up",// Color del boton de confirmación y Hover
            }
        });
    });

	//Alerta de cuestioranio para confirmar una accion
	window.addEventListener('alertaConfirmacionUsuario', event => {
		// alert('Name updated to: ' + event.detail.id);
		Swal.fire({
			title: event.detail.title,
			text: event.detail.text,
			icon: event.detail.icon,
			showCancelButton: true,
			confirmButtonText: event.detail.confirmButtonText,
			cancelButtonText: event.detail.cancelButtonText,
			customClass: {
                confirmButton: "btn btn-"+event.detail.confirmButtonColor+" hover-elevate-up",
                cancelButton: "btn btn-"+event.detail.cancelButtonColor+" hover-elevate-up",
            }
		}).then((result) => {
			if (result.isConfirmed) {
				Livewire.emitTo('modulo-administrador.gestion-usuarios.usuario.index', 'cambiarEstado', event.detail.id);
			}
		});
	});

</script>
@endsection