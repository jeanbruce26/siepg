@extends('layouts.modulo-administrador')

@section('content')

@livewire('modulo-administrador.gestion-usuarios.trabajador.index')

@endsection

@section('javascript')
<script>
    //TRABAJADOR
    window.addEventListener('modalTrabajador', event => {
        $('#modalTra').modal('hide');
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
                confirmButton: "btn btn-"+event.detail.color,
            }
        });
    });

    //ASIGANAR TRABAJADOR
    window.addEventListener('modalAsignar', event => {
        $('#modalAsignar').modal('hide');
    })

    window.addEventListener('notificacionAsignar', event => {
        Toastify({
            text: event.detail.message,
            close: true,
            duration: 5000,
            stopOnFocus: true,
            newWindow: true,
            style: {
                background: "#2eb867",
            }
        }).showToast();
    })

    //alerta cambiar estado trabajador
    window.addEventListener('alertaConfirmacionTrabajador', event => {
        // alert('Name updated to: ' + event.detail.id);
        Swal.fire({
            title: '¿Estás seguro de modificar el estado del trabajador?',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Modificar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('modulo-administrador.usuario.trabajador', 'cambiarEstado', event.detail.id);
            }
        })
    })

    //alerta cambiar estado trabajador asignado
    window.addEventListener('alertaConfirmacionTrabajadorAsignado', event => {
        Swal.fire(
        'Para desactivar un trabajador primero se tiene que desasignar sus cargos.',
        '',
        'warning'
        )
    })

    //DESASIGANAR TRABAJADOR
    window.addEventListener('modaldDesAsignar', event => {
        $('#modaldDesAsignar').modal('hide');
    })

    //alerta para desasignar trabajador
    window.addEventListener('alertaDesasignarTrabajador', event => {
        // alert('Name updated to: ' + event.detail.id);
        Swal.fire({
            title: '¿Estás seguro de desasignar sus cargos al trabajador?',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Desasignar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('modulo-administrador.usuario.trabajador', 'desasignarTrabajador');
            }
        })
    })
</script>
@endsection