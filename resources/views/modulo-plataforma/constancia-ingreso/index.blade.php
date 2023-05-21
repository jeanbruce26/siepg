@extends('layouts.modulo-plataforma')
@section('title', 'Constancia de Ingreso - Plataforma Escuela de Posgrado')
@section('content')
@livewire('modulo-plataforma.constancia-ingreso.index')
@endsection
@section('scripts')
<script>
    // window.addEventListener('modal_pago_plataforma', event => {
    //     $('#modal_pago_plataforma').modal(event.detail.action);
    // })
    window.addEventListener('alerta_constancia', event => {
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
    window.addEventListener('alerta_constancia_2', event => {
        // alert('Name updated to: ' + event.detail.id);
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            showCancelButton: true,
            confirmButtonText: event.detail.confirmButtonText,
            cancelButtonText: event.detail.cancelButtonText,
            // confirmButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
            // cancelButtonClass: 'hover-elevate-up', // Hover para elevar boton al pasar el cursor
            customClass: {
                confirmButton: "btn btn-"+event.detail.confirmButtonColor,
                cancelButton: "btn btn-"+event.detail.cancelButtonColor,
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Livewire.emitTo('modulo-plataforma.pagos.index', 'guardar_pago');
            }
        });
    });
    window.addEventListener('alerta_final_constancia', event => {
        let timerInterval;
        Swal.fire({
            title: 'Espere un momento, estamos generando su constancia de ingreso',
            showConfirmButton: false,
            timer: 5000,
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
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                Livewire.emitTo('modulo-plataforma.constancia-ingreso.index', 'generar_constancia');
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
            }
        })
    });
</script>
@endsection
