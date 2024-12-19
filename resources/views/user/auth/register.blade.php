@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4>User Registration Form</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="access_token" value="{{ $invitation->token }}">
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>Firstname</label>
                                    <input type="text" class="form-control" name="firstname"
                                        value="{{ old('firstname') }}">
                                    @error('firstname')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label>Lastname</label>
                                    <input type="text" class="form-control" name="lastname"
                                        value="{{ old('lastname') }}">
                                    @error('lastname')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username"
                                        value="{{ old('username') }}">
                                    @error('username')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}">
                                    @error('mobile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image">
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <button type="submit" class="btn btn-success btn-sm w-100">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
