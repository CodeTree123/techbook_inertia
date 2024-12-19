@extends('admin.layoutsNew.app')
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('assetsNew/main_css/customer/edit_site.css') }}">
@endsection
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 8px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 72%;
        }
    </style>
    <div class="content-wrapper" style="background-color: white;">
        @include('admin.includeNew.breadcrumb')
        <div class="container-fluid text-dark">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-gray">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between">
                                    <h3>Site Information</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <p><b>Site ID: </b>{{ $edit->site_id }}</p>
                                    <p><b>Company Name: </b>{{ @$edit->customer->company_name }}</p>
                                    <p><b>Standard Rate: </b>{{ @$edit->customer->s_rate }}</p>
                                    <p><b>Address 2: </b>{{ $edit->address_2 }}</p>
                                    <p><b>Timezone: </b>{{ $edit->time_zone }}</p>
                                    <p><b>Property Description: </b>{{ $edit->description }}</p>
                                </div>

                                <div class="col-6">
                                    <p><b>Emergency Rate: </b>{{ @$edit->customer->e_rate }}</p>
                                    <p><b>Location Name: </b>{{ $edit->location }}</p>
                                    <p><b>Address 1: </b>{{ $edit->address_1 }}</p>
                                    <p><b>City: </b>{{ $edit->city }}</p>
                                    <p><b>State: </b>{{ $edit->state }}</p>
                                    <p><b>Zip Code: </b>{{ $edit->zipcode }}</p>
                                </div>

                                <div class="col-12">
                                    <form action="{{ route('customer.site.delete', ['id' => $edit->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-gray">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between">
                                    <h4><span class="badge bg-success">{{ @$edit->customer->company_name }}</span> Site
                                        Edit</h4>
                                    <div>
                                        <a href="{{ route('customer.customerZone', ['id' => $edit->customer->id]) }}"
                                            class="btn btn-primary me-2">Customer Info</a>
                                        <a class="btn btn-primary" href="{{ route('customer.site.list') }}">Site List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-dark">
                            <input type="hidden" name="cusGenId" id="cusGenId"
                                value="{{ $edit->customer->customer_id }}">
                            <input type="hidden" name="cusId" id="cusId" value="{{ $edit->customer->id }}">
                            <input type="hidden" name="cusName" id="cusName"
                                value="{{ $edit->customer->company_name }}">
                            <form action="{{ route('customer.editSitePost', ['id' => $edit->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Customer</label>
                                        <select class="form-control" name="customer_id" id="customer" style="width:100%">
                                            <option value="">Select Customer</option>
                                        </select>
                                        @error('customer_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Site Id</label>
                                        <input type="text" class="form-control" value="{{ $edit->site_id }}"
                                            name="site_id">
                                        @error('site_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Location Name</label>
                                        <input type="text" class="form-control" name="location"
                                            placeholder="Enter Location name...." value="{{ $edit->location }}"
                                            id="location">
                                        @error('location')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Address 1</label>
                                        <input type="text" class="form-control" name="address_1"
                                            placeholder="Enter address 1...." value="{{ $edit->address_1 }}"
                                            id="latitude">
                                        @error('address_1')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Address 2</label>
                                        <input type="text" class="form-control" name="address_2"
                                            placeholder="Enter address 2...." value="{{ $edit->address_2 }}"
                                            id="longitude">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">City</label>
                                        <input type="text" class="form-control" name="city"
                                            placeholder="Enter city...." value="{{ $edit->city }}">
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">State</label>
                                        <input type="text" class="form-control" name="state"
                                            placeholder="Enter state...." value="{{ $edit->state }}">
                                        @error('state')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">Timezone</label>
                                        <input type="text" class="form-control" placeholder="Enter timezone...."
                                            value="{{ $edit->time_zone }}" name="time_zone">
                                        @error('timezone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="">Zip Code</label>
                                        <input type="text" class="form-control" name="zipcode"
                                            placeholder="Enter zip...." value="{{ $edit->zipcode }}">
                                        @error('zipcode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="">Property Description</label>
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="5"
                                            value="{{ $edit->description }}">{{ $edit->description }}</textarea>
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary w-100">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let customerId = $("#cusId").val();
            let customerName = $("#cusName").val();
            let cusGenId = $("#cusGenId").val();
            let cusFullName = cusGenId + " - " + customerName;

            $("#customer").select2({
                ajax: {
                    url: "{{ route('customer.get') }}",
                    type: "GET",
                    dataType: "JSON",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                    placeholder: 'Search for a customer',
                    minimumInputLength: 1,
                }
            });

            var option = new Option(cusFullName, customerId, true, true);
            $("#customer").append(option).trigger('change');
        });
    </script>
@endsection
