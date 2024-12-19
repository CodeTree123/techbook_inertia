<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}">
    <link rel="stylesheet" href="https://jonthornton.github.io/jquery-timepicker/jquery.timepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assetsNew/dist/css/jodit.fat.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    @include('user.include.css')
    @stack('style-lib')
    @stack('style')
</head>

<body>
    <div id="app">
        @include('user.include.header')
        <main class="py-1 m-1">
            <div class="">@include('admin.partials.breadcrumb')</div>
            @include('partials.notify')
            @include('user.include.preloader')
            @include('user.include.conversionAlertUser')
            @include('user.subTicket.modal')
            @include('user.sites.modal')
            @include('user.technicians.modal')
            @include('user.customers.modal')
            @yield('content')
        </main>
        @include('user.include.footer')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assetsNew/dist/js/jodit.fat.min.js') }}"></script>

    @stack('custom_script')
    @stack('site_modal_script')
    @stack('technician_modal_script')
    @stack('customer_modal_script')
    @stack('script-lib')
    @stack('script')
</body>

</html>