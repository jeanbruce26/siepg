@extends('layouts.modulo-docente')
@section('title', 'Matriculados - Docente de Posgrado - Escuela de Posgrado')
@section('content')
@livewire('modulo-docente.matriculados.index', ['id_docente_curso' => $id_docente_curso], key($id_docente_curso))
@endsection
@section('scripts')
    <script>
        window.addEventListener('modal_nota', event => {
            $('#modal_nota').modal(event.detail.action);
        })
        window.addEventListener('alerta_matriculados', event => {
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
        window.addEventListener('alerta_matriculados_opciones', event => {
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
                    Livewire.emitTo('modulo-docente.matriculados.index', 'asignar_nsp');
                }
            });
        });
        window.addEventListener('descargar_actas', event => {
            event.detail.array_archivos.forEach(element => {
                // console.log(element);
                const a = document.createElement('a');
                a.href = element.url;
                a.download = element.nombre;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        });
    </script>
@endsection
