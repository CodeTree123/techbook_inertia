<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="https://kit.fontawesome.com/76c40a5f57.js" crossorigin="anonymous"></script>
<link href="https://pro.fontawesome.com/releases/v5.1.0/css/all.css" rel=" stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<style>
    @media screen and (max-width: 767px) {
        .logo img {
            max-width: 100px;
            margin-left: -200px;
            margin-top: 30px;

        }
    }

    @media screen and (max-width: 767px) {
        .border-none-vertical {
            display: none;

        }

        #cssmenu .submenu-button.submenu-opened {
            background: #7EC8E3;
        }

        #cssmenu ul ul li a {
            color: black;
            background: #7EC8E3;
        }

        #cssmenu .submenu-button {
            border: none;
        }
    }
</style>
<header class="fixed-top">

    <style>
        .shadow-circle {
            border-radius: 12px;
            /* box-shadow: 0px 0px 10px rgba(102, 160, 202, 0.5); */
        }

        .shadow-circle:hover {
            box-shadow: 0px 0px 15px rgba(102, 160, 202, 0.7);
        }

        .dropdown-menu.show {
            display: block;
            padding-right: 70px;
            /* padding-bottom: 40px; */
            padding-left: 10px;
        }
    </style>
    <div class="p-1 d-flex justify-content-end " style="background: #AFE1AF	;  position: relative">
        <a class="" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <span>
                <h6 style="font-size: 15px;" class="mx-3 mb-0">

                    <button class="btn btn-outline-light shadow-circle text-dark" style="margin-left: 10px;"> <i class="fas fa-user-circle text-success" style="margin-right: 10px; "></i>
                        {{ @Auth::user()->fullname }}</button>
                </h6>
            </span>

        </a>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="position: absolute;">

            <a class="dropdown-item" href="{{ route('user.profile.setting') }}">
                @lang('Profile Setting')
            </a>
            <a class="dropdown-item mt-3" href="{{ route('user.twofactor') }}">
                @lang('2FA Security')
            </a>
            <a class="dropdown-item mt-3" href="{{ route('user.change.password') }}">
                @lang('Change Password')
            </a>

            <hr>
            <a class="dropdown-item mt-3 text-danger" href="{{ route('user.logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('user.logout') }}" method="get" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    <div class="header-main shadow bg-white">
        <div class=" d-flex align-items-center ">
            <div class="logo">
                <a href="{{ route('user.home') }}"><img src="{{asset('landingPage/images/tb_logo.jpeg')}}"></a>
            </div>

            <div class="border-none-vertical" style=" margin-left:50px; border-left: 4px solid #F2F2F2;"></div>

            <style>
                .navbar-container {
                    display: flex;
                    justify-content: center;
                }

                @media screen and (min-width: 1920px) {
                    .navbar-container {
                        display: flex;
                        justify-content: center;
                    }
                }
            </style>
            <nav class="navbar-container">
                <nav id='cssmenu'>
                    <div id="head-mobile"></div>
                    <div class="button"></div>
                    <ul>
                        @guest
                        @if (Route::has('user.login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('user.register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li><a href='#'>Work Order</a>
                            <ul>
                                <li><a href='#'><i class="fa-solid fa-envelope-open"></i> <span class="mx-3"> New</span></a>
                                    <ul>
                                        <li><a href="#" id="serviceButton" class="actionButton" data-action="service"><i class="fa-brands fa-servicestack"></i> <span class="mx-3">Service</span></a></li>
                                        <li><a href="#" id="projectButton" class="actionButton" data-action="project"><i class="fa-solid fa-bars-progress"></i> <span class="mx-3"> Project</span></a></li>
                                        <li><a href="#" id="installButton" class="actionButton" data-action="install"><i class="fa-brands fa-instalod"></i> <span class="mx-3"> install</span></a></li>
                                    </ul>
                                <li><a href='{{route('user.work.order.list.inertia')}}' id=""> <i class="fas fa-search"></i> <span class="mx-3">Search</span></a></li>
                                
                                <li><a href="{{route('user.home')}}"> <i class="fas fa-home"></i> <span class="mx-3">Home</span></a></li>
                            </ul>
                        </li>

                        <li><a href='#'>Site</a>
                            <ul>
                                <li><a href='#' id="siteNewButton"><i class="fa-solid fa-envelope-open"></i> <span class="mx-3">New</span></a></li>
                                <li><a href='#' id="siteSearchButton"><i class="fas fa-search"></i> <span class="mx-3">Search</span></a>
                                <li><a href='#' id="siteImportButton"><i class="fa-solid fa-file-import"></i> <span class="mx-3">import</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a href='#'>Field Techs</a>
                            <ul>
                                <li><a href='#' id="techNewButton"><i class="fa-solid fa-envelope-open"></i> <span class="mx-3">New</span></a></li>
                                <li><a href='#' id="techSearchButton"> <i class="fas fa-search"></i> <span class="mx-3">Search</span></a>
                                <li><a href='#' id="techZipCodeButton"> <i class="fas fa-search"></i> <span class="mx-3">Zip Code</span></a>
                                <!-- <li><a href='#' id="techMeasureButton"> <i class="fas fa-search"></i> <span class="mx-3">Distance Measure Search</span></a> -->
                                    {{-- <li><a href='#' id="techImportButton"><i class="fa-solid fa-file-import"></i><span class="mx-3">Import</span></a> --}}
                                <li><a href='#' id="techDistanceButton"><i class="fa-solid fas fa-clone"></i><span class="mx-2">Distance Search</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a href='#'>Report</a>
                            <ul>
                                <li><a href='#'> <i class="fa-solid fa-temperature-empty"></i> <span class="mx-3">empty</span></a></li>
                                <li><a href='#'> <i class="fa-solid fa-temperature-empty"></i> <span class="mx-3">empty</span></a></li>
                        </li>
                    </ul>
                    </li>
                    <li><a href='#'>Create Sub Ticket</a>
                        <ul>
                            <li><a href='#' id="subTicketButton"> <i class="fa-solid fa-plus"></i> <span class="mx-3">Create</span></a></li>
                            <li><a href='#'><i class="fa-solid fa-ticket"></i> <span class="mx-3"> Sub Ticket</span></a>
                            </li>
                        </ul>
                    </li>
                    <li><a href='#'>Estimator</a>
                        <ul>
                            <li><a href='#'><i class="fa-solid fa-mask-ventilator"></i> <span class="mx-3">New</span></a></li>
                            <li><a href='#'> <i class="fas fa-search
                        "></i> <span class="mx-3"> Search</span></a>
                            </li>
                        </ul>
                    </li>
                    <li><a href='#'>Parts</a>
                        <ul>
                            <li><a href='#'>New Parts</a></li>
                            <li><a href='#'>Search Part</a>
                            </li>
                        </ul>
                    </li>

                    <li><a href='#'>Customer</a>
                        <ul>
                            <li><a href='#' id='cusNewButton'>New Customer</a></li>
                            <li><a href='#' id='cusSearchButton'>Search Customer</a></li>
                            {{-- <li><a href='#' id='cusImportButton'>Import</a></li> --}}
                        </ul>
                    </li>
                    <li><a href='#'>Quotes</a>
                        <ul>
                            <li><a href='#'>New Quotes </a></li>
                            <li><a href='#'> Search Quotes </a>
                            </li>
                        </ul>
                    </li>
                    @endguest
                    </ul>
                </nav>
            </nav>
        </div>
    </div>

</header>





<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
</div>
