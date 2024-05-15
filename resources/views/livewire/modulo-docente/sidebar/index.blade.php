<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-8" id="kt_app_sidebar_logo">
        <a href="{{ route('docente.inicio') }}">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-light.png') }}" height="42" class="app-sidebar-logo-default theme-light-show">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-dark.png') }}" height="42" class="app-sidebar-logo-default theme-dark-show">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-pg.png') }}" height="30" class="app-sidebar-logo-minimize">
        </a>
    </div>
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <div class="mb-5 d-flex flex-column gap-4">
                        <div class="symbol symbol-100px text-center">
                            <img src="{{ $trabajador->trabajador_perfil_url ? asset($trabajador->trabajador_perfil_url) : $trabajador->avatar }}"
                                alt="avatar" />
                        </div>
                        <span class="fs-2 fw-bold text-center">
                            {{ $trabajador->grado_academico->grado_academico_prefijo }} {{ $trabajador->trabajador_nombre_completo }}
                        </span>
                        <span class="badge badge-light-info py-3 d-flex justify-content-center fs-7">
                            {{ $tipo_trabajador->tipo_trabajador }}
                        </span>
                        <button type="button" wire:click="cerrar_sesion"
                            class="btn btn-flex flex-center btn-dark btn-custom text-nowrap px-0 h-40px w-100">
                            <span class="btn-label">
                                Cerrar sesión
                            </span>
                        </button>
                    </div>
                    <hr>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('docente.inicio') || request()->routeIs('docente.matriculados') ? 'active' : '' }}" href="{{ route('docente.inicio') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-book fs-1"></i>
                        </span>
                        <span class="menu-title fw-semibold fs-4">Inicio</span>
                    </a>
                </div>
                {{-- <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-5">Menu</span>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
