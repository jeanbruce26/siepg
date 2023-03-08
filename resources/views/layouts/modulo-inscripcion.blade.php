<html lang="es" >
<head><base href=""/>
    <title>
        {{ config('app.name', 'Escuela de Posgrado - UNU') }}
    </title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8"/>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/logo-pg.png') }}"/>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>        <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets_2/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets_2/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets_2/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets_2/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Global Stylesheets Bundle-->

    <!-- Resource Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- LIVEWIRE STYLES -->
    @livewireStyles
</head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" data-kt-app-header-stacked="true" data-kt-app-header-primary-enabled="true" data-kt-app-header-secondary-enabled="true" class="app-default">
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if ( document.documentElement ) {
                if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if ( localStorage.getItem("data-bs-theme") !== null ) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                } if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>

		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header border-bottom shadow-none">
					<!--begin::Header primary-->
					<div class="app-header-primary" data-kt-sticky="true" data-kt-sticky-name="app-header-primary-sticky" data-kt-sticky-offset="{default: 'false', lg: '300px'}">
						<!--begin::Header primary container-->
						<div class="app-container container-xxl d-flex align-items-stretch justify-content-between">
							<!--begin::Logo and search-->
							<div class="d-flex flex-grow-1 flex-lg-grow-0">
								<!--begin::Logo wrapper-->
								<div class="d-flex align-items-center me-7" id="kt_app_header_logo_wrapper">
									<!--begin::Header toggle-->
									<button class="d-lg-none btn btn-icon btn-color-gray-600 btn-active-color-primary w-35px h-35px ms-n2 me-2" id="kt_app_header_menu_toggle">
										<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
										<span class="svg-icon svg-icon-2">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
												<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</button>
									<!--end::Header toggle-->
									<!--begin::Logo-->
									<a href="../../demo35/dist/index.html" class="d-flex align-items-center me-lg-20 me-5">
										<img alt="Logo" src="{{ asset('assets/media/logos/logo-pg.png') }}" height="35" class="d-sm-none d-inline" />
										<img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-light.png') }}" height="45" class="theme-light-show d-none d-sm-inline" />
										<img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-dark.png') }}" height="45" class="theme-dark-show d-none d-sm-inline" />
									</a>
									<!--end::Logo-->
								</div>
								<!--end::Logo wrapper-->
							</div>
							<!--end::Logo and search-->
							<!--begin::Navbar-->
							<div class="app-navbar flex-shrink-0">
								<!--begin::User menu-->
								<div class="app-navbar-item ms-3 ms-lg-9" id="kt_header_user_menu_toggle">
									<button class="btn btn-danger">
                                        Cerrar sesión
                                    </button>
								</div>
								<!--end::User menu-->
							</div>
							<!--end::Navbar-->
						</div>
						<!--end::Header primary container-->
					</div>
					<!--end::Header primary-->
				</div>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <div class="border-bottom shadow-sm">
                        <div class="container-xxl px-10 py-7">
                            <div class="d-flex flex-column align-items-center">
                                <h1 class="text-center">
                                    Escuela de Posgrado de la Universidad Nacional de Ucayali
                                </h1>
                                <span class="fw-bolder fs-3">
                                    Proceso de Admisión 2024 - I
                                </span>
                            </div>
                        </div>
                    </div>
					<!--begin::Wrapper container-->
					<div class="app-container container-xxl d-flex flex-row flex-column-fluid">
						<!--begin::Main-->
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<!--begin::Content-->
								<div id="kt_app_content" class="app-content flex-column-fluid">
									<!--begin::Row-->
									<div class="row g-5 gx-xl-10">
                                        {{-- <div class="col-xl-12">
                                            <div class="card shadow-sm">
                                                <div class="px-6 py-5">
                                                    <span class="fw-bolder fs-3">
                                                        Inicio
                                                    </span>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-xl-12">
                                            <div class="card shadow-sm" style="background-color: #ffffeb">
                                                <div class="px-6 py-5">
                                                    <span class="fw-bolder fs-4">
                                                        Estimado/a postulante:
                                                    </span>
                                                    <p>
                                                        <ul class="fs-6">
                                                            <li class="mb-3">
                                                                Si usted realizó el pago por concepto de inscripción, deberá habilitar su comprobante de pago o voucher,
                                                                <strong>dando click en el botón "REGISTRAR PAGO"</strong> ubicado en la parte inferior.
                                                                Una vez que haya habilitado su voucher, podrá continuar con el proceso de inscripción mediante esta plataforma.
                                                            </li>
                                                            <li class="mb-3">
                                                                Una vez que finalice el proceso, se generará su ficha de inscripción correspondiente.
                                                            </li>
                                                            <li class="mb-3">
                                                                Cualquier incidencia o consulta, puede comunicarse a <strong>admision_posgrado@unu.edu.pe</strong>
                                                            </li>
                                                        </ul>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="card shadow-sm" style="background-color: #f1fcf0">
                                                <div class="px-6 py-5">
                                                    <span class="fw-bolder fs-4">
                                                        Recomendación antes de comenzar su inscripción:
                                                    </span>
                                                    <p>
                                                        <ul class="fs-6">
                                                            <li class="mb-3">
                                                                Puedes realizar tu inscripción al día siguiente de haber realizado tu pago.
                                                            </li>
                                                            <li class="mb-3">
                                                                <strong>Ten a mano tu Documento de Identidad.</strong> <br>
                                                                La información solicitada debe ser escrita tal cual este en el.
                                                            </li>
                                                            <li class="mb-3">
                                                                <strong>Proporciona datos fidedignos (auténticos).</strong> <br>
                                                                Recuerda que la información que proporciones sera derivada a la <strong>Oficina Central de Admisión</strong>
                                                            </li>
                                                            <li class="mb-3">
                                                                <strong>Se muy cuidadoso al completar cada información solicidad por el Sistema de Inscripción.</strong> <br>
                                                                Ya que, la información proporcionada tiene caracter de <strong>Declaración Jurada.</strong>
                                                            </li>
                                                        </ul>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
									</div>
									<!--end::Row-->
								</div>
								<!--end::Content-->
							</div>
							<!--end::Content wrapper-->
							<!--begin::Footer-->
							<div id="kt_app_footer" class="app-footer align-items-center justify-content-center justify-content-md-between flex-column flex-md-row py-3 py-lg-6">
								<!--begin::Copyright-->
								<div class="text-dark order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
									<a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">
                                        Escuela de Posgrado - UNU
                                    </a>
								</div>
								<!--end::Copyright-->
							</div>
							<!--end::Footer-->
						</div>
						<!--end:::Main-->
					</div>
					<!--end::Wrapper container-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>

        <!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->

        <!--begin::Javascript-->
        <script> var hostUrl = "assets/"; </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('assets_2/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets_2/js/scripts.bundle.js') }}"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="{{ asset('assets_2/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
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
        <script src="{{ asset('assets_2/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="{{ asset('assets_2/js/widgets.bundle.js') }}"></script>
        <script src="{{ asset('assets_2/js/custom/widgets.js') }}"></script>
        <script src="{{ asset('assets_2/js/custom/apps/chat/chat.js') }}"></script>
        <!--end::Custom Javascript-->
        <script type="module">
        import hotwiredTurbo from 'https://cdn.skypack.dev/@hotwired/turbo';
        </script>
        <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbolinks-eval="false" data-turbo-eval="false"></script>
        <!-- LIVEWIRE SCRIPTS -->
        @livewireScripts
        <!--end::Javascript-->
    </body>
    <!--end::Body-->
</html>
