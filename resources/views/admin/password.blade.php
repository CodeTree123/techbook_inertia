@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">Change Password</div>
                    <div class="card-body">
                        <form action="{{ route('admin.password.update') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="old_password">Old Password</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Password</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection