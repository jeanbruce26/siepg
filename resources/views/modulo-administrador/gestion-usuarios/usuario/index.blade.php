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
			confirmButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color,
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
			confirmButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
			cancelButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
			customClass: {
                confirmButton: "btn btn-"+event.detail.confirmButtonColor,
                cancelButton: "btn btn-"+event.detail.cancelButtonColor,
            }
		}).then((result) => {
			if (result.isConfirmed) {
				Livewire.emitTo('modulo-administrador.gestion-usuarios.usuario.index', 'cambiarEstado', event.detail.id);
			}
		});
	});

</script>
@endsection