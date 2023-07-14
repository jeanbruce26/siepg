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
    window.addEventListener('registro-alumnos', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            buttonsStyling: false,
            confirmButtonText: event.detail.confirmButtonText,
            customClass: {
                confirmButton: "btn btn-"+event.detail.color+" hover-elevate-up",
            }
        });
    })
</script>
@endsection