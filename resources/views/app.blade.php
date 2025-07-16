<!DOCTYPE html>
<html lang="en">

<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ !empty($setting) && $setting != '' ? $setting['value'] : '' }}</title>
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <!--begin::Fonts-->
    {{ Metronic::getGoogleFontsInclude() }}
    <!--end::Fonts-->
    <style type="text/css">
        .custom_td {
            border-left: lightgrey 1px solid;
        }
        .name_ellipsis_modual{
            white-space: nowrap !important;
            max-width: 160px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important; 
            display: inline-block;
        }
        .display-filter {
            display: none;
        }
        .thead--is-fixed{
            display: table;
            position: fixed !important;
            top: 80px;
        }

        thead{
            position: static;
            top: 0;
        }
        .thead{ z-index: 15;} .tbody{ z-index: 15;}
        /* .dataTables_scrollBody{
            overflow-y: unset !important;
        } */
        @media screen and (max-width: 1200px) {
            .repeater-scrolling-wrapper {
                overflow-x: scroll;
                overflow-y: hidden;
                white-space: nowrap;

                .card {
                    display: inline-block;
                }
            }
        }
        }

        /*    background-color: #eee5ff;*/
    </style>
    <!--end::Page Vendors Styles-->

    {{-- Global Theme Styles (used by all pages) --}}
    @foreach (config('layout.resources.css') as $style)
        <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}"
            rel="stylesheet" type="text/css" />
    @endforeach
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
    <!--end::Layout Themes-->
    <!-- calender -->
    <link rel="stylesheet" href="{{ asset('fullcalendar/fullcalendar.bundle.css') }}" type="text/css">
    @php
        $company_favicon = '';
        if (isset($companylogo) && !empty($companylogo)) {
            $company_favicon = $companylogo['company_favicon'] ?? '';
        }
    @endphp
    <link rel="shortcut icon" href="{{ asset('') }}{{ $company_favicon }}" />
    {{-- Includable CSS --}}
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link href="{{ asset('css/jquery.fancybox.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}" type="text/css">

    @yield('styles')
</head>

<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
    <div class="loading full-page-loader" style="display: none;">Loading&#8230;</div>
    @php
        $defultYear = getDefaultYear();
        $formDate = $toDate = '';
        if ($defultYear) {
            $formDate = $defultYear->from_date;
            $toDate = $defultYear->to_date;
        }
    @endphp
    @include('layout')
    <!--[html-partial:include:{"file":"layout.html"}]/-->
    @include('partials._extras.offcanvas.quick-notifications')
    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-notifications.html"}]/-->
    @include('partials._extras.offcanvas.quick-actions')
    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-master.html"}]/-->
    @include('partials._extras.offcanvas.quick-master')
    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-actions.html"}]/-->
    @include('partials._extras.offcanvas.quick-user')
    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-user.html"}]/-->
    @include('partials._extras.offcanvas.quick-panel')
    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-panel.html"}]/-->
    @include('partials._extras.scrolltop')
    <!--[html-partial:include:{"file":"partials/_extras/scrolltop.html"}]/-->
    <script>
        var HOST_URL = "";
    </script>

    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1200
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#8950FC",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#F3F6F9",
                        "dark": "#212121"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1E9FF",
                        "secondary": "#ECF0F3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#212121",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#ECF0F3",
                    "gray-300": "#E5EAEE",
                    "gray-400": "#D6D6E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#80808F",
                    "gray-700": "#464E5F",
                    "gray-800": "#1B283F",
                    "gray-900": "#212121"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->

    <!--end::Global Config-->

    {{-- Global Theme JS Bundle (used by all pages)  --}}
    @foreach (config('layout.resources.js') as $script)
        <script src="{{ asset($script) }}" type="text/javascript"></script>
    @endforeach
    <script src="{{ asset($script) }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    <!-- Sweetalert -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Validation -->
    <script src="{{ asset('js/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/fancybox.min.js') }}"></script>



    <!--end::Page Scripts-->
    {{-- Includable JS --}}
    <script type="text/javascript">
        var page_show_entriess = parseInt("{{ config('srtpl.settings.page_show_entries', 25) }}");

        $('#addModalDisabled').removeClass('disabled');
        /*
        const toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 8000
        });
        */

        $('.select2').select2();


        const toast = toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        const message = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success shadow-sm mr-2',
                cancelButton: 'btn btn-danger shadow-sm'
            },
            buttonsStyling: false,
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if (Session::has('error'))
            toastr.error("{!! session('error') !!}", "Error");
            @php
                session()->forget('error');
            @endphp
        @endif

        @if (Session::has('success'))
            toastr.success("{!! session('success') !!}", "Success");
            @php
                session()->forget('success');
            @endphp
        @endif

        $('.defult-date').attr('min', '{{ $formDate }}');
        $('.defult-date').attr('max', '{{ $toDate }}');

        var defaultFromDate = "{{ $formDate }}";
        var defaultToDate = "{{ $toDate }}";

        /** Indian standard currency format in js */
        function indianCurrencyFormat(x) {
            return x.toString().split('.')[0].length > 3 ? x.toString().substring(0, x.toString().split('.')[0].length - 3)
                .replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + x.toString().substring(x.toString().split('.')[0].length -
                    3) : x.toString();
        }
        /** Indian standard currency format in js */

        // Convert number value to specified precision without round off
        function numberFormatPrecision(value, precision = 0) {
            if (!isNaN(value) && !isNaN(precision)) {
                const v = (typeof value === 'string' ? value : value.toString()).split('.');
                if (precision <= 0) return v[0];
                let f = v[1] || '';
                if (f.length > precision) return `${v[0]}.${f.substr(0,precision)}`;
                while (f.length < precision) f += '0';
                return `${v[0]}.${f}`;
            }
            return '';
        }

        function digitFormat(currency) {
            return number = Number(currency.replace(/[^0-9.-]+/g, ""));
        }
        function strpad(str, max) {
            str = str.toString();
            return str.length < max ? strpad("0" + str, max) : str;
        }
        function numberFormatPrecision(value, precision = 0) {
            if (!isNaN(value) && !isNaN(precision)) {
                const v = (typeof value === 'string' ? value : value.toString()).split('.');
                if (precision <= 0) return v[0];
                let f = v[1] || '';
                if (f.length > precision) return `${v[0]}.${f.substr(0,precision)}`;
                while (f.length < precision) f += '0';
                return `${v[0]}.${f}`;
            }
            return '';
        }
        // Allow Only Integer
        $(document).on("keypress",'.jsOnlyNumber',function(event){
            if(event.which < 48 || event.which > 58){
                return false;
            }
        });
        // Allow Only One Decimal Number
        $(document).on("keypress",'.jsOneDecimal',function(event){
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && ((event.which < 48 || event.which >
                    57) && (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }

            var text = $(this).val();
            if ((text.indexOf('.') != -1) && (text.substring(text.indexOf('.')).length > 1) && (event.which != 0 &&
                    event.which != 8) && ($(this)[0].selectionStart >= text.length - 1)) {
                event.preventDefault();
            }
        });
        // Allow Only Two Decimal Number
        $(document).on("keypress",'.jsTwoDecimal',function(event){
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && ((event.which < 48 || event.which >
                    57) && (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }

            var text = $(this).val();
            if ((text.indexOf('.') != -1) && (text.substring(text.indexOf('.')).length > 2) && (event.which != 0 &&
                    event.which != 8) && ($(this)[0].selectionStart >= text.length - 2)) {
                event.preventDefault();
            }
        });
        // Allow Only Three Decimal Number
        $(document).on("keypress",'.jsThreeDecimal',function(event){
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && ((event.which < 48 || event.which >
                    57) && (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }

            var text = $(this).val();
            if ((text.indexOf('.') != -1) && (text.substring(text.indexOf('.')).length > 3) && (event.which != 0 &&
                    event.which != 8) && ($(this)[0].selectionStart >= text.length - 3)) {
                event.preventDefault();
            }
        });
        function showFullPageLoader(){
            $('.full-page-loader').show();
        }
        function hideFullPageLoader(){
            $('.full-page-loader').hide();
        }
    </script>
    @stack('scripts')
    @yield('scripts')
    <script src="{{ asset('js/action.js') }}"></script>
    <script src="{{ asset('js/shortcuts.js') }}"></script>
    <script type="text/javascript">

        jQuery(window).bind('load', function () {
            // $(document).ready(function() {
            
            shortcut.add("Alt+A", function () {
                
                if (jQuery('.addSrt').html()) {
                    window.location = jQuery('.addSrt').attr('href');
                }
            });

            shortcut.add("Alt+S", function () {
                console.log('Alt+S');
                jQuery('form input[name="saveBtn"],.saveBtn').click();
            });
            /*
             *
             * When press alt+w then save and exit
             *
             */
            shortcut.add("Alt+W", function () {
                console.log('Alt+W');
                jQuery('form input[name="saveExitBtn"],.saveExitBtn').click();
            });
            /*
             *
             * When press alt+w then Back to Last screen
             *
             */
            shortcut.add("Alt+B", function () {
                // console.log('Alt+C');
                if (jQuery('.backSrt').html()) {
                    window.location = jQuery('.backSrt').attr('href');
                }
            });
        }); 
        function logOutConfirm() {
            message.fire({
                title: 'Are you sure',
                text: 'Do you want to logout?',
                type: 'warning',
                customClass: {
                    confirmButton: 'btn btn-success shadow-sm mr-2',
                    cancelButton: 'btn btn-danger shadow-sm'
                },
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if(result.value){
                    window.location.href = "{{ url('logout') }}";
                }                
            });
        }
    </script>
</body>

<!--end::Body-->

</html>
