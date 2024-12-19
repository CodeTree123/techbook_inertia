@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">Edit Employee</div>
                    <div class="card-body">
                        <form action="{{ route('employee.update', $employees->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="form-label">Name</div>
                                <input type="text" name="name" class="form-control" value="{{$employees->name}}">
                            </div>
                            <div class="form-group">
                                <div class="form-label">Email</div>
                                <input type="text" name="email" class="form-control" value="{{$employees->email}}">
                            </div>
                            <div class="form-group">
                                <div class="form-label">Mobile</div>
                                <input type="text" name="mobile" class="form-control" value="{{$employees->mobile}}">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection