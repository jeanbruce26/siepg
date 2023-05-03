<!DOCTYPE html>
<html lang="es">

<!--begin::Head-->
<head>
	<title>
        @yield('title', 'Escuela de Posgrado - UNU')
    </title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8"/>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/logo-pg.png') }}"/>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>        <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Global Stylesheets Bundle-->

    <!-- Resource Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- LIVEWIRE STYLES -->
    @livewireStyles
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
	<script>
        var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }
    </script>
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<div id="kt_app_header" class="app-header bg-light-primary">
				<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
					<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
						<div class="btn btn-icon btn-active-color-primary w-35px h-35px"
							id="kt_app_sidebar_mobile_toggle">
							<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
							<span class="svg-icon svg-icon-2 svg-icon-md-1">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
										fill="currentColor" />
									<path opacity="0.3"
										d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
										fill="currentColor" />
								</svg>
							</span>
							<!--end::Svg Icon-->
						</div>
					</div>
					<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
						<a href="#" class="d-lg-none">
							<img alt="Logo" src="{{ asset('assets/media/logos/logo-pg.png') }}" class="h-30px" />
						</a>
					</div>
					<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
						id="kt_app_header_wrapper">
						<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
							data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
							data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
							data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
							data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
							data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                            <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
                                <div class="menu-item">
                                    @php $admision = App\Models\Admision::where('admision_estado', 1)->first()->admision; @endphp
                                    <span class="fs-3 fw-bold">
                                        PROCESO DE {{ $admision }}
                                    </span>
                                </div>
                            </div>
						</div>
						<!--end::Menu wrapper-->
						@livewire('modulo-administrador.navbar.index')
					</div>
					<!--end::Header wrapper-->
				</div>
				<!--end::Header container-->
			</div>
			<!--end::Header-->
			<!--begin::Wrapper-->
			<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				<!--begin::Sidebar-->
				<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true"
					data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
					data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
					data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
					<!--begin::Logo-->
					<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
						<!--begin::Logo image-->
						<a href="../../demo1/dist/index.html">
							<img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-light.png') }}" height="42" class="app-sidebar-logo-default theme-light-show">
							<img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-dark.png') }}" height="42" class="app-sidebar-logo-default theme-dark-show">
							<img alt="Logo" src="{{ asset('assets/media/logos/logo-pg.png') }}" height="30" class="app-sidebar-logo-minimize">
						</a>
						<!--end::Logo image-->
						<!--begin::Sidebar toggle-->
						<div id="kt_app_sidebar_toggle"
							class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
							data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
							data-kt-toggle-name="app-sidebar-minimize">
							<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
							<span class="svg-icon svg-icon-2 rotate-180">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path opacity="0.5"
										d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
										fill="currentColor" />
									<path
										d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
										fill="currentColor" />
								</svg>
							</span>
							<!--end::Svg Icon-->
						</div>
						<!--end::Sidebar toggle-->
					</div>
					<!--end::Logo-->
					<!--begin::sidebar menu-->
					<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
						<!--begin::Menu wrapper-->
						<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
							data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
							data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
							data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
							data-kt-scroll-save-state="true">
							<!--begin::Menu-->
							<div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
								data-kt-menu="true" data-kt-menu-expand="false">
								<div class="menu-item">
									<!--begin:Menu link-->
									<a class="menu-link {{ request()->is('administrador') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.dashboard') }}">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
											<span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
													<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
													<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
													<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Dashboard</span>
									</a>
									<!--end:Menu link-->
								</div>
								<!--end:Menu item-->
								<!--begin:Menu item-->
								<div class="menu-item pt-5">
									<!--begin:Menu content-->
									<div class="menu-content">
										<span class="menu-heading fw-bold text-uppercase fs-7">Modulos</span>
									</div>
									<!--end:Menu content-->
								</div>
								<!--end:Menu item-->
								<!--begin:Menu item-->
								<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('administrador/usuario') || request()->is('administrador/trabajador') ? 'active show border-2 border-start border-gray-300 rounded' : ''}}">
									<!--begin:Menu link-->
									<span class="menu-link">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/layouts/lay008.svg-->
											<span class="svg-icon svg-icon-2  {{ request()->is('administrador/usuario') || request()->is('administrador/trabajador') ? 'text-primary' : ''}}">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
													<rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
													<path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
													<rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Gestión de Usuarios</span>
										<span class="menu-arrow"></span>
									</span>
									<!--end:Menu link-->
									<!--begin:Menu sub-->
									<div class="menu-sub menu-sub-accordion">
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/usuario') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.usuario') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Usuario</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/trabajador') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.trabajador') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Trabajador</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
									</div>
									<!--end:Menu sub-->
								</div>
								<!--end:Menu item-->

								<!--begin:Menu item-->
								<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('administrador/sede') || request()->is('administrador/plan')  || request()->is('administrador/programa')  || request()->is('administrador/admision') ? 'active show border-2 border-start border-gray-300 rounded' : ''}}">
									<!--begin:Menu link-->
									<span class="menu-link">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/layouts/lay008.svg-->
											<span class="svg-icon svg-icon-2 {{ request()->is('administrador/sede') || request()->is('administrador/plan') || request()->is('administrador/programa') || request()->is('administrador/admision') ? 'text-primary' : ''}}"">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M6.5 11C8.98528 11 11 8.98528 11 6.5C11 4.01472 8.98528 2 6.5 2C4.01472 2 2 4.01472 2 6.5C2 8.98528 4.01472 11 6.5 11Z" fill="currentColor"></path>
													<path opacity="0.3" d="M13 6.5C13 4 15 2 17.5 2C20 2 22 4 22 6.5C22 9 20 11 17.5 11C15 11 13 9 13 6.5ZM6.5 22C9 22 11 20 11 17.5C11 15 9 13 6.5 13C4 13 2 15 2 17.5C2 20 4 22 6.5 22ZM17.5 22C20 22 22 20 22 17.5C22 15 20 13 17.5 13C15 13 13 15 13 17.5C13 20 15 22 17.5 22Z" fill="currentColor"></path>
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Gestión Curricular</span>
										<span class="menu-arrow"></span>
									</span>
									<!--end:Menu link-->
									<!--begin:Menu sub-->
									<div class="menu-sub menu-sub-accordion">
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/sede') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.sede') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Sede</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/plan') ? 'active border-3 border-start border-primary' : '' }}"" href="{{ route('administrador.plan') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Plan</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/programa') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.programa') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Programa</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/admision') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.admision') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Admisión</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
									</div>
									<!--end:Menu sub-->
								</div>
								<!--end:Menu item-->

								<!--begin:Menu item-->
								<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('administrador/inscripcion-pago') || request()->is('administrador/inscripcion')  || request()->is('administrador/programa')  || request()->is('administrador/admision') ? 'active show border-2 border-start border-gray-300 rounded' : ''}}">
									<!--begin:Menu link-->
									<span class="menu-link">
										<span class="menu-icon">
											<!--begin::Svg Icon-->
											<span class="svg-icon svg-icon-muted svg-icon-2 {{ request()->is('administrador/sede') || request()->is('administrador/plan') ? 'text-primary' : ''}}"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M3 6C2.4 6 2 5.6 2 5V3C2 2.4 2.4 2 3 2H5C5.6 2 6 2.4 6 3C6 3.6 5.6 4 5 4H4V5C4 5.6 3.6 6 3 6ZM22 5V3C22 2.4 21.6 2 21 2H19C18.4 2 18 2.4 18 3C18 3.6 18.4 4 19 4H20V5C20 5.6 20.4 6 21 6C21.6 6 22 5.6 22 5ZM6 21C6 20.4 5.6 20 5 20H4V19C4 18.4 3.6 18 3 18C2.4 18 2 18.4 2 19V21C2 21.6 2.4 22 3 22H5C5.6 22 6 21.6 6 21ZM22 21V19C22 18.4 21.6 18 21 18C20.4 18 20 18.4 20 19V20H19C18.4 20 18 20.4 18 21C18 21.6 18.4 22 19 22H21C21.6 22 22 21.6 22 21ZM16 11V9C16 6.8 14.2 5 12 5C9.8 5 8 6.8 8 9V11C7.2 11 6.5 11.7 6.5 12.5C6.5 13.3 7.2 14 8 14V15C8 17.2 9.8 19 12 19C14.2 19 16 17.2 16 15V14C16.8 14 17.5 13.3 17.5 12.5C17.5 11.7 16.8 11 16 11ZM13.4 15C13.7 15 14 15.3 13.9 15.6C13.6 16.4 12.9 17 12 17C11.1 17 10.4 16.5 10.1 15.7C10 15.4 10.2 15 10.6 15H13.4Z" fill="currentColor"/>
												<path d="M9.2 12.9C9.1 12.8 9.10001 12.7 9.10001 12.6C9.00001 12.2 9.3 11.7 9.7 11.6C10.1 11.5 10.6 11.8 10.7 12.2C10.7 12.3 10.7 12.4 10.7 12.5L9.2 12.9ZM14.8 12.9C14.9 12.8 14.9 12.7 14.9 12.6C15 12.2 14.7 11.7 14.3 11.6C13.9 11.5 13.4 11.8 13.3 12.2C13.3 12.3 13.3 12.4 13.3 12.5L14.8 12.9ZM16 7.29998C16.3 6.99998 16.5 6.69998 16.7 6.29998C16.3 6.29998 15.8 6.30001 15.4 6.20001C15 6.10001 14.7 5.90001 14.4 5.70001C13.8 5.20001 13 5.00002 12.2 4.90002C9.9 4.80002 8.10001 6.79997 8.10001 9.09997V11.4C8.90001 10.7 9.40001 9.8 9.60001 9C11 9.1 13.4 8.69998 14.5 8.29998C14.7 9.39998 15.3 10.5 16.1 11.4V9C16.1 8.5 16 8 15.8 7.5C15.8 7.5 15.9 7.39998 16 7.29998Z" fill="currentColor"/>
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Inscripción</span>
										<span class="menu-arrow"></span>
									</span>
									<!--end:Menu link-->
									<!--begin:Menu sub-->
									<div class="menu-sub menu-sub-accordion">
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/inscripcion') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.inscripcion') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Inscripción</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/inscripcion-pago') ? 'active border-3 border-start border-primary' : '' }}"" href="{{ route('administrador.inscripcion-pago') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Inscripción de Pago</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
										<!--begin:Menu item-->
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link {{ request()->is('administrador/programa') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.programa') }}">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Pago</span>
											</a>
											<!--end:Menu link-->
										</div>
										<!--end:Menu item-->
									</div>
									<!--end:Menu sub-->
								</div>
								<!--end:Menu item-->

                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ request()->is('administrador/admitido') ? 'active border-3 border-start border-primary' : '' }}" href="{{ route('administrador.admitido') }}">
                                        <span class="menu-icon">
                                            <span class="svg-icon svg-icon-2">
                                                <svg width="26" height="28" viewBox="0 0 26 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17.8254 27.3337H4.17203C3.1536 27.3337 2.17688 26.9291 1.45674 26.2089C0.736602 25.4888 0.332031 24.5121 0.332031 23.4937V4.50699C0.332031 3.48856 0.736602 2.51184 1.45674 1.7917C2.17688 1.07156 3.1536 0.666992 4.17203 0.666992H17.8254C18.8438 0.666992 19.8205 1.07156 20.5407 1.7917C21.2608 2.51184 21.6654 3.48856 21.6654 4.50699V23.4937C21.6654 24.5121 21.2608 25.4888 20.5407 26.2089C19.8205 26.9291 18.8438 27.3337 17.8254 27.3337ZM10.9987 7.33366C10.4713 7.33366 9.95571 7.49006 9.51718 7.78307C9.07865 8.07609 8.73685 8.49257 8.53502 8.97984C8.33319 9.46711 8.28038 10.0033 8.38327 10.5206C8.48616 11.0378 8.74014 11.513 9.11308 11.8859C9.48602 12.2589 9.96117 12.5129 10.4785 12.6158C10.9957 12.7186 11.5319 12.6658 12.0192 12.464C12.5065 12.2622 12.9229 11.9204 13.216 11.4818C13.509 11.0433 13.6654 10.5277 13.6654 10.0003C13.6654 9.29308 13.3844 8.6148 12.8843 8.11471C12.3842 7.61461 11.7059 7.33366 10.9987 7.33366ZM15.3587 19.3337C15.5794 19.347 15.8 19.3052 16.0005 19.2121C16.2011 19.1189 16.3753 18.9774 16.5076 18.8002C16.6398 18.623 16.7259 18.4157 16.7581 18.1969C16.7903 17.9781 16.7676 17.7548 16.692 17.547C16.2027 16.4571 15.3998 15.5378 14.3858 14.9061C13.3718 14.2744 12.1926 13.9591 10.9987 14.0003C9.81328 13.9677 8.64459 14.2856 7.63898 14.9141C6.63337 15.5426 5.83553 16.4538 5.34537 17.5337C4.9987 18.4003 5.70536 19.3337 7.38536 19.3337H15.3587ZM24.332 12.667H22.9987V18.0003H24.332C24.6857 18.0003 25.0248 17.8598 25.2748 17.6098C25.5249 17.3598 25.6654 17.0206 25.6654 16.667V14.0003C25.6654 13.6467 25.5249 13.3076 25.2748 13.0575C25.0248 12.8075 24.6857 12.667 24.332 12.667ZM24.332 4.66699H22.9987V10.0003H24.332C24.6857 10.0003 25.0248 9.85985 25.2748 9.6098C25.5249 9.35975 25.6654 9.02061 25.6654 8.66699V6.00033C25.6654 5.6467 25.5249 5.30756 25.2748 5.05752C25.0248 4.80747 24.6857 4.66699 24.332 4.66699Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                        </span>
                                        <span class="menu-title">Admitido</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
								<!--begin:Menu item-->
								<div class="menu-item">
									<!--begin:Menu link-->
									<a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs"
										target="_blank">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
											<span class="svg-icon svg-icon-2">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
													xmlns="http://www.w3.org/2000/svg">
													<path opacity="0.3"
														d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
														fill="currentColor" />
													<path
														d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
														fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Documentation</span>
									</a>
									<!--end:Menu link-->
								</div>
								<!--end:Menu item-->
							</div>
							<!--end::Menu-->
						</div>
						<!--end::Menu wrapper-->
					</div>
					<!--end::sidebar menu-->
				</div>
				<!--end::Sidebar-->

				<!--begin::Main-->
				<div class="app-main flex-column flex-row-fluid bg-light-primary" id="kt_app_main">
					@yield('content')
				</div>
				<!--end:::Main-->

				<!--begin::Footer-->
				<div id="kt_app_footer" class="app-footer">
					<!--begin::Footer container-->
					<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
						<!--begin::Copyright-->
						<div class="text-dark order-2 order-md-1">
							<span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
							<a href="#" class="text-gray-800 text-hover-primary">Escuela de Posgrado</a>
						</div>
						<!--end::Copyright-->
					</div>
					<!--end::Footer container-->
				</div>
				<!--end::Footer-->

			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::App-->

	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
		<span class="svg-icon">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)"
					fill="currentColor" />
				<path
					d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
					fill="currentColor" />
			</svg>
		</span>
		<!--end::Svg Icon-->
	</div>
	<!--end::Scrolltop-->

	<!--begin::Javascript-->
	<script> var hostUrl = "assets/"; </script>

	<!--begin::Global Javascript Bundle(mandatory for all pages)-->
	<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
	<!--end::Global Javascript Bundle-->

	<!--begin::Vendors Javascript(used for this page only)-->
	<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
	<!--end::Vendors Javascript-->

	<!--begin::Custom Javascript(used for this page only)-->
	<script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
	<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
	<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
	<script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
	<script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
	<script src="{{ asset('assets/js/custom/utilities/modals/new-target.js') }}"></script>
	<script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
	<!--end::Custom Javascript-->

	<!-- LIVEWIRE SCRIPTS -->
    @stack('scripts')
	@yield('javascript')
	@livewireScripts
	<!--end::Javascript-->
</body>
<!--end::Body-->

</html>
