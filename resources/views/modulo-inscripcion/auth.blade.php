@extends('layouts.auth-modulo-inscripcion')
@section('content')
<div class="border-bottom shadow-sm" style="background: white">
    <div class="container-xxl px-10 py-7">
        <div class="d-flex justify-content-around align-items-center">
            <div>
                <img src="{{ asset('assets/media/logos/logo-pg.png') }}" alt="Logo" height="70" />
            </div>
            <div class="d-flex flex-column align-items-center">
                <h1 class="text-center">
                    Escuela de Posgrado de la Universidad Nacional de Ucayali
                </h1>
                <span class="fw-bolder fs-3 text-center">
                    Proceso de {{ $admision }}
                </span>
            </div>
            <div>
                <img src="{{ asset('assets/media/logos/logo-unu.png') }}" alt="Logo" height="70" />
            </div>
        </div>
    </div>
</div>
<!--begin::Wrapper container-->
<div class="app-container d-flex flex-row flex-column-fluid">
    <div class="container-xxl">
        <!--begin::Main-->
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Row-->
                    @livewire('modulo-inscripcion.auth')
                    <!--end::Row-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Content wrapper-->
        </div>
        <!--end:::Main-->
    </div>
</div>
<!--end::Wrapper container-->
@endsection
