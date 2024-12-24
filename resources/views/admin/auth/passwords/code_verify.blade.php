@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="login-main" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container custom-container d-flex justify-content-center">
            <div class="login-area">
                <div class="text-center mb-3">
                    <h2 class="text-white mb-2">@lang('Verify Code')</h2>
                    <p class="text-white mb-2">@lang('Please check your email and enter the verification code you got in your email.')</p>
                </div>
                <form action="{{ route('admin.password.verify.code') }}" method="POST" class="login-form w-100">
                    @csrf

                    <div class="code-box-wrapper d-flex w-100">
                        <div class="form-group mb-3 flex-fill">
                            <span class="text-white fw-bold">@lang('Verification Code')</span>
                            <div class="verification-code">
                                <input type="text" name="code" class="overflow-hidden" autocomplete="off">
                                <div class="boxes">
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between">
                        <a href="{{ route('admin.password.reset') }}" class="forget-text">@lang('Try to send again')</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-4">@lang('Submit')</button>
                </form>
                <a href="{{ route('admin.login') }}" class="text-white mt-4"><i class="las la-sign-in-alt" aria-hidden="true"></i>@lang('Back to Login')</a>
            </div>
        </div>
    </div>
</div>


@endsection


