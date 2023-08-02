@extends('layouts.modulo-inscripcion')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl py-8">
            @livewire('modulo-inscripcion.registro-alumnos.index')
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    window.addEventListener('modal', event => {   
        $(event.detail.titleModal).modal('hide');
    })

    //Evento para abrir el modal
    window.addEventListener('abrir-modal', event => {   
        $(event.detail.titleModal).modal('show');
    })

    window.addEventListener('registro_inscripcion', event => {
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

    window.addEventListener('alerta_final_registro', event => {
        let timerInterval;
        Swal.fire({
            title: 'Guardando los datos del registro, espere un momento por favor',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            padding: '2em 2em 3em 2em',
            didOpen: () => {
                Swal.showLoading()
            },
            willClose: () => {
                let id_persona = event.detail.id_persona;

                // Redirigir a la página final después de que se cierre la alerta
                window.location.href = "{{ route('posgrado.gracias', ['id' => ':id_persona']) }}".replace(':id_persona', id_persona);
            }

        })
    });
    
</script>
@endsection