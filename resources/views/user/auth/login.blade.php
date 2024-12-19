@extends('layouts.app')
@section('content')

<style>
    .side-img {
        margin-top: 225px;
    }
</style>

<body class="vh-100" style="background-color: #00008b;">
    <section>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-6"> <!-- Adjusted column width to col-md-6 for half width on medium screens -->
                    <div class="card o-hidden border-0 shadow-lg" style="border-radius: 1rem;">
                        <div class="card-body p-0">
                            <div class="row  g-0" style="background-color: #1cc88a;border-radius: 1rem;">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <form method="POST" action="{{ route('user.login') }}">
                                        @csrf
                                        <div class="row justify-content-center p-3">

                                            <div class="form-group">
                                                <label class="form-label text-white">@lang('User Name/Email')</label>
                                                <input type="text" class="form-control" name="username" value="{{ old('username') }}">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label text-white">@lang('Password')</label>
                                                <input type="password" class="form-control " name="password">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="  form-check-label text-white" for="remember">
                                                    @lang('Remember Me')
                                                </label>
                                                <button type="submit" class="btn btn-primary w-100 mt-4">
                                                    @lang('Login')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small text-white" href="{{ route('user.password.request') }}">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
@endsection