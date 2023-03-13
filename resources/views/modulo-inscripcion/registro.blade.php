@extends('layouts.modulo-inscripcion')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl py-8">
            @livewire('modulo-inscripcion.registro', [
                'id_inscripcion' => $id_inscripcion
            ])
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    window.addEventListener('modal_registro_expediente', event => {
        $('#modal_registro_expediente').modal(event.detail.action);
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
            title: 'Espere un momento, estamos generando su ficha de inscripción',
            showConfirmButton: false,
            timer: 20000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            padding: '2em 2em 3em 2em',
            didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
            }, 100)
            },
            willClose: () => {
            clearInterval(timerInterval)
            }
        })
    });
</script>
@endsection
