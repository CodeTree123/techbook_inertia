<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin - Login</title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .side-img {
            margin-top: 225px;
        }
    </style>
</head>

<body class="vh-100" style="background-color: #00008b;">
    <section>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card o-hidden border-0 shadow-lg" style="border-radius: 1rem;">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-md-6 col-lg-5 side-img d-none d-md-block">
                                    <!-- You can replace the image source with your actual image -->
                                    <img src="{{ asset('assetsNew/dist/img/h_banner_1.png') }}" alt="Logo"
                                        class="img-fluid d-flex align-items-start" />
                                </div>
                                <div class="col-md-6 col-lg-7 d-flex align-items-center bg-success">
                                    <div class="card-body p-4 p-lg-5 text-black">
                                        <form class="user" action="{{ route('admin.postlogin') }}" method="POST">
                                            @csrf
                                            <div class="d-flex align-items-center mb-3 pb-1">
                                                <img src="{{ asset('assetsNew/dist/img/mainlogo.png') }}" alt="Logo"
                                                    class="img-fluid"
                                                    style="border-radius: 1rem; max-height: 100%; background-color: #fff; border: 1px solid #ccc; box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);" />

                                            </div>
                                            <h4 class="text-white fw-normal mb-3 pb-3" style="letter-spacing: 1px;">
                                                Admin Login</h4>
                                            @if (Session::has('message'))
                                                <p class="alert alert-info">{{ Session::get('message') }}</p>
                                            @endif
                                            <div class="form-outline mb-4">
                                                <input type="text" class="form-control form-control-lg"
                                                    id="form2Example17" name="username" placeholder="Username" />
                                                @error('username')
                                                    <span class="text-white">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-outline mb-4">
                                                <input type="password" class="form-control form-control-lg"
                                                    id="form2Example27" name="password" placeholder="Password" />
                                                @error('password')
                                                    <span class="text-white">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <x-captcha />
                                            <div class="pt-1 mb-4">
                                                <button class="btn btn-primary btn-lg btn-block"
                                                    type="submit">Login</button>
                                            </div>
                                            <div>
                                                <a class="small text-muted"
                                                    href="{{ route('admin.password.reset') }}"><b
                                                        style="color: white;">Forgot Password?</b></a>
                                            </div>

                                        </form>
                                        <p class="" style="color: white;">Don't have an account?
                                        </p>
                                        <a href="{{ route('admin.register') }}">Register </a>
                                        <a href="#" class="small text-muted">Terms of use.</a>
                                        <a href="#" class="small text-muted text- white">Privacy policy</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

</body>

</html>
