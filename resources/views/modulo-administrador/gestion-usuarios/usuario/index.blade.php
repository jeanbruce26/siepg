@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.gestion-usuarios.usuario.index')

<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
            <a href="#" class="text-gray-800 text-hover-primary">Escuela de Posgrado</a>
        </div>
        <!--end::Copyright-->
    </div>
    <!--end::Footer container-->
</div>
<!--end::Footer-->

@endsection

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.38/dist/sweetalert2.all.min.js"></script>
<script>
	window.addEventListener('modalUsuario', event => {
		$('#modalUsuario').modal('hide');
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
    })

	//Alerta de cuestioranio para confirmar una accion
	window.addEventListener('alertaConfirmacionUsuario', event => {
		// alert('Name updated to: ' + event.detail.id);
		Swal.fire({
			title: '¿Estás seguro de modificar el estado del usuario?',
			text: "",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Modificar',
			cancelButtonText: 'Cancelar',
			confirmButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
			cancelButtonClass: 'hover-elevate-up' // Hover para elevar boton al pasar el cursor
		}).then((result) => {
			if (result.isConfirmed) {
				Livewire.emitTo('modulo-administrador.gestion-usuarios.usuario.index', 'cambiarEstado', event.detail.id);
			}
		})
	})
</script>
@endsection