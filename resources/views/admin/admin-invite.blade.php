@extends('admin.layoutsNew.app')
@section('content')
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Send Admin Registration Invitation</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('send.admin.invite') }}" method="post">
                                @csrf
                                <div class="form-group col-4">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" name="email"
                                        placeholder="Enter the mail address to send invite">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send
                                        Invitation</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
