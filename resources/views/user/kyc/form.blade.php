@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 ">
                <div class="card bg-light">
                    <div class="card-header bg-primary">
                        <h5 class="card-title text-white">@lang('KYC Form')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <x-viser-form identifier="act" identifierValue="kyc" />

                            <div class="form-group">
                                <button type="submit" class="btn btn-success w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
