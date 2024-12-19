@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color:white">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->
    <div class="container">
        <div class="row">
            <div class="card">

                <div class="card-body">
                    <form action="{{url('technician/cat/edit/post/')}}/{{$edit->id}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" name="skill_name" value="{{$edit->skill_name}}">
                        </div>
                        <button class="btn btn-primary w-100 mt-2"><i class="fas fa-sync-alt"></i>
                            Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection