@extends('layouts.modulo-inscripcion')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl py-8">
            @livewire('modulo-inscripcion.registro-docente.index')
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    window.addEventListener('modal', event => {
        $(event.detail.id).modal(event.detail.action);
    });

    window.addEventListener('alerta', event => {
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

    window.addEventListener('alerta-contador', event => {
        let timerInterval;
        Swal.fire({
            title: 'Guardando los datos del registro, espere un momento por favor',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            padding: '2em 2em 3em 2em',
            didOpen: () => {
                Swal.showLoading()
            },
            willClose: () => {
                let id_docente = event.detail.id_docente;

                // Redirigir a la página final después de que se cierre la alerta
                window.location.href = "{{ route('posgrado.credenciales-email.docente', ['id' => ':id_docente']) }}".replace(':id_docente', id_docente);
            }

        })
    });

</script>
@endsection
