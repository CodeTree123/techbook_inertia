@extends('admin.layoutsNew.app')

@section('content')
    <style>
        .form-control {
            border: 1px solid black;
        }
    </style>
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="row ">
                                <div class="col-md-12 d-flex justify-content-between">
                                    <h3></h3>
                                    <a href="{{ url('/customer/order/view') }}/{{ $edit->id }}"
                                        class="btn btn-primary">Go Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('customer/post/edit') }}/{{ $edit->id }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="project_manager" class="form-label">Project Manager</label>
                                        <input type="text" class="form-control" name="project_manager"
                                            value="{{ @$edit->customer->project_manager }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ @$edit->customer->phone }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ @$edit->customer->email }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="location" class="form-label">Site Location</label>
                                        <input type="text" class="form-control" name="location"
                                            value="{{ @$edit->site->location }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="address_1" class="form-label">Address 1</label>
                                        <input type="text" class="form-control" name="address_1"
                                            value="{{ @$edit->site->address_1 }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="address_2" class="form-label">Address 2</label>
                                        <input type="text" class="form-control" name="address_2"
                                            value="{{ @$edit->site->address_2 }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" name="city"
                                            value="{{ @$edit->site->city }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="state" class="form-label">State</label>
                                        <input type="text" class="form-control" name="state"
                                            value="{{ @$edit->site->state }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="zipcode" class="form-label">Zip Code</label>
                                        <input type="text" class="form-control" name="zipcode"
                                            value="{{ @$edit->site->zipcode }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="h_operation" class="form-label">Hours Of Operation</label>
                                        <input type="text" class="form-control" name="h_operation"
                                            value="{{ @$edit->h_operation }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="main_tel" class="form-label">Main Telephone</label>
                                        <input type="text" class="form-control" name="main_tel"
                                            value="{{ @$edit->main_tel }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="site_contact_name" class="form-label">Site Contact Name</label>
                                        <input type="text" class="form-control" name="site_contact_name"
                                            value="{{ @$edit->site_contact_name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="site_contact_phone" class="form-label">Site Contact Phone</label>
                                        <input type="number" class="form-control" name="site_contact_phone"
                                            value="{{ @$edit->site_contact_phone }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_schedule" class="form-label">Date Schedule</label>
                                        <input type="text" class="form-control" name="date_schedule"
                                            value="{{ @$edit->date_schedule }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="e_checkin" class="form-label">Earliest CheckIn</label>
                                        <input type="text" class="form-control" name="e_checkin"
                                            value="{{ @$edit->e_checkin }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="l_checkin" class="form-label">Latest CheckIn</label>
                                        <input type="text" class="form-control" name="l_checkin"
                                            value="{{ @$edit->l_checkin }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="instruction" class="form-label">Instruction</label>
                                        <textarea name="instruction" class="form-control" id="" cols="30" rows="5">{{ @$edit->instruction }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="a_instruction" class="form-label">Additional Instruction</label>
                                        <textarea name="a_instruction" class="form-control" id="" cols="30" rows="5">{{ @$edit->a_instruction }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="r_tools" class="form-label">Required Tools</label>
                                        <textarea name="r_tools" class="form-control" id="" cols="30" rows="5">{{ @$edit->r_tools }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="scope_work" class="form-label">Scope Of Work</label>
                                        <textarea name="scope_work" class="form-control" id="" cols="30" rows="5">{{ @$edit->scope_work }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="arrival" class="form-label">Upon Arrival On Site</label>
                                        <textarea name="arrival" class="form-control" id="" cols="30" rows="5">{{ @$edit->arrival }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="leaving" class="form-label">Before leaving Site</label>
                                        <textarea name="leaving" class="form-control" id="" cols="30" rows="5">{{ @$edit->leaving }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="picture" class="form-label">Attached Picture</label>
                                        @if ($imageFileNames != null)
                                            @foreach ($imageFileNames as $imageName)
                                                <img src="{{ asset('imgs/' . $imageName) }}" alt="Random Image"
                                                    width="250px" height="150px">
                                            @endforeach
                                        @else
                                            <h3>No Image Data</h3>
                                        @endif
                                        <input type="file" name="pictures[]" class="form-control" multiple>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <h3 class="text-center mt-5"><span class="badge-danger">Danger Zone</span></h3>
                            <a href="{{ url('/customer/order/delete') }}/{{ $edit->id }}"
                                class="btn btn-danger w-100 mt-5" data-toggle="modal"
                                data-target="#confirmationModal-{{ $edit->id }}"><i class="fas fa-trash-alt"></i>
                                Remove This Work Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmationModal-{{ $edit->id }}" tabindex="-1" role="dialog"
            aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Work Order?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="{{ url('/customer/order/delete') }}/{{ $edit->id }}"
                            class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
