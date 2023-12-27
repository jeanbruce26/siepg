@extends('layouts.modulo-inscripcion')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl py-8">
            @livewire('modulo-inscripcion.gracias', [
                'id_inscripcion' => $id_inscripcion
            ])
        </div>
    </div>
</div>
@endsection
