@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success"></div>
                    <div class="card-body">
                    <div class="form-group col-6">
                        <h6>Name : <span id="name"></span></h6>
                        <h6>Username : <span id="username"></span></h6>
                        <h6>Usertype : <span id="usertype"></span></h6>
                        <h6>Email : <span id="email"></span></h6>
                        <h6>Status : <span class="badge badge-success" id="status"></span></h6>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection