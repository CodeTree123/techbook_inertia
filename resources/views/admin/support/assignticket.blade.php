@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1>Search Vendors nearby</h1>
                </div>
                <div class="card-body">
                    <form action="">
                        <input type="search" class="form-control" placeholder="Find by ZIP code" name="search" value="{{ request('search') }}">
                    </form>
                    <ul class="list-group mt-3">
                        @forelse($users as $user)
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <li class="list-group-item">{{ $user->email }}</li>
                            </div>
                            <div class="col-md-2">
                                <a href="tel:{{ $user->mobile }}" class="d-none d-lg-inline-block btn btn-sm btn-secondary shadow-lg"><i class="fa fa-bell fa-sm text-white-50"></i> @lang('Call')</a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.users.notification.log',$user->id) }}" class="d-none d-lg-inline-block btn btn-sm btn-secondary shadow-lg"><i class="fa fa-bell fa-sm text-white-50"></i> @lang('Notifications')</a>
                            </div>
                        </div>
                        @empty
                        <li class="list-group-item list-group-item-danger">Technician Not Found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1>Assign Ticket to Vendor</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('contact.submit')}}" method="post">
                        @csrf
                        <div class="row ">
                            <div class="form-group col-md-6">
                                <label for="email" class="form-label">Vendor Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" placeholder="Subject">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="site_id" class="form-label">Site Id</label>
                                <input type="text" class="form-control" name="site_id" placeholder="Site id">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mobile" class="form-label">Message</label>
                                <textarea type="number" class="form-control" name="message" placeholder="message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection