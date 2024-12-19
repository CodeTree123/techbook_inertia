<!DOCTYPE html>
<html lang="en">
<head>
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>
    <link rel="icon" type="image/png" href="path/to/your/icon.png"> 
    @stack('style-lib')
    @stack('style')
    <!-- Custom fonts for this template-->
    @include('admin.include.css')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/select2.min.css')}}">
</head>

</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('admin.include.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('partials.notify')
                @include('admin.include.topbar')
                @include('admin.partials.breadcrumb')
                @yield('content')
                @include('admin.include.footer')
                <!-- End of Footer -->
            </div>
        </div>

    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    @include('admin.include.js')
    @stack('script-lib')
    @stack('script')
</body>

</html>