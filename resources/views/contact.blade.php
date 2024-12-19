@extends('layouts.app')
@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h1 class="">Contact Form</h1>
                </div>
                    <div class="card-body">
                        <form action="{{route('contact.submit')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" name="subject" id="subject">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">
                                    <label for="message">Mesasge</label>
                                    <textarea type="text" class="form-control" name="message" id="message"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Submit</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection