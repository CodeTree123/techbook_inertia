@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Verify Token</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.verify.token') }}" method="post">
                            @csrf
                            <div class="form-group col-12">
                                <label>Enter your access token</label>
                                <input type="text" class="form-control" name="access_token">
                                @error('access_token')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-12 text-center mt-3">
                                <button type="submit" class="btn btn-success btn-sm w-100">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
