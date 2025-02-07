@php
$general = gs();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>{{$general->site_name}}</title>
    <link rel="shortcut icon" type="image/png" href="{{getImage(getFilePath('logoIcon') .'/favicon.png')}}">
    <!-- slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="{{asset('landingPage/css/bootstrap.css')}}" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{asset('landingPage/css/style.css')}}" rel="stylesheet" />
    <!-- responsive style -->
    <link href="{{asset('landingPage/css/responsive.css')}}" rel="stylesheet" />
</head>

<body>
    <div class="hero_area" style="background: #AFE1AF	;  position: relative">
        <!-- header section strats -->
        <header class="header_section">
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand" href="/">
                        <span>
                            {{$general->site_name}}
                        </span>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="s-1"> </span>
                        <span class="s-2"> </span>
                        <span class="s-3"> </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex ml-auto flex-column flex-lg-row align-items-center">
                            <ul class="navbar-nav  ">

                                <li class="nav-item active">
                                    @auth
                                    <a class="nav-link" href="{{route('user.work.order.list.inertia')}}">Home <span class="sr-only">(current)</span></a>
                                    @else
                                    <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>

                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('contact') }}"> Contact</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.login') }}"> Login </a>
                                </li>
                                @if (Route::has('user.register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.register') }}"> Register </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
        @endif
        <!-- end header section -->
        <!-- slider section -->
        <section class=" slider_section ">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="detail_box">
                            <h1>
                                Tech <br>
                                Service <br>
                                Providers
                            </h1>
                            <a href="">
                                Contact Us
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 offset-lg-1">
                        <div class="img_content">
                            <div class="img_container">
                                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <div class="img-box">
                                                <img src="{{asset('landingPage/images/slider-img.jpg')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="img-box">
                                                <img src="{{asset('landingPage/images/slider-img.jpg')}}" alt="">
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="img-box">
                                                <img src="{{asset('landingPage/images/slider-img.jpg')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev " href="#carouselExampleControls" role="button" data-slide="prev">
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- end slider section -->
    </div>

    <!-- info section -->

    <section class="info_section layout_padding" style="background: #AFE1AF	;  position: relative">
        <div class="container">
            <div class="info_contact">
                <div class="row">
                    <div class="col-md-4">
                        <a href="">
                            <img src="{{asset('landingPage/images/location-white.png')}}" alt="">
                            <span class="text-dark">
                                1905 Marketview Dr.
                                Suite 226
                                Yorkville, IL 60560
                            </span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="">
                            <img src="{{asset('landingPage/images/telephone-white.png')}}" alt="">
                            <span class="text-dark">
                                Call : 630.474.5234
                            </span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="">
                            <img src="{{asset('landingPage/images/envelope-white.png')}}" alt="">
                            <span class="text-dark">
                                info@techyeahinc.com
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-lg-9">
                    <div class="info_form">
                        <form action="{{route('subscriberAdd')}}" method="post">
                            @csrf
                            <input type="text" name="email" placeholder="Enter your email">
                            <button type="submit">
                                subscribe
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="info_social">
                        <div>
                            <a href="">
                                <img src="{{asset('landingPage/images/fb.png')}}" alt="">
                            </a>
                        </div>
                        <div>
                            <a href="">
                                <img src="{{asset('landingPage/images/twitter.png')}}" alt="">
                            </a>
                        </div>
                        <div>
                            <a href="">
                                <img src="{{asset('landingPage/images/linkedin.png')}}" alt="">
                            </a>
                        </div>
                        <div>
                            <a href="">
                                <img src="{{asset('landingPage/images/instagram.png')}}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- end info section -->

    <!-- footer section -->
    <footer class="container-fluid footer_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-9 mx-auto">
                    <p>
                        &copy; 2025 All Rights Reserved By
                        <a href="">{{$general->site_name}}</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer section -->


    <script src="{{asset('landingPage/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('landingPage/js/bootstrap.js')}"></script>

</body>

</html>