<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    {{-- <link rel="stylesheet" href="{{ base_path('resources/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ base_path('public/css/custom-pdf.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-pdf.css') }}">
    <style type="text/css" media="all">
        * {
            color: black !important;
        }

        body {
            margin-left: 0px;
            margin-right: 0px;
        }

        .table-bordered {
            border: 0.8px solid black !important;
        }

        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th {
            border-bottom-width: 1px;
            padding: 2px;
            border: 0.8px solid black !important;
            font-size: 8px;
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th {
            padding: 2px;
            font-size: 8px;
            border: 0.8px solid black !important;
        }

        .bold {
            font-weight: bold;
        }

        .no-print {
            display: none;
        }

        .no-padding {
            padding: 0px;
        }

        .grey-back {
            background: #E6E6E6;
        }
    </style>
    @section('style')
    @show
</head>

<body>
    <!-- Page Container -->
    <div id="page-container">
        <!--  Content -->
        @yield('content')
    </div>
    <!-- END Page Container -->
</body>
@stack('scripts')

</html>
