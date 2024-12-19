@extends('admin.layoutsNew.app')
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<link rel="stylesheet" href="{{asset('assetsNew/main_css/customer/vendor_care.css')}}">
@endsection
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <!-- /.content-header -->
    <div class="container-fluid text-dark">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-mb-12 d-flex justify-content-between">
                        <h3 class="text-dark"></h3>
                        <div class="">
                            <div class="button btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fas fa-wrench"></i>

                                Add Vendor List Care</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <table id="example" class="table-responsive table-hover" style="width:100%">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">#SL</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Site Name</th>
                            <th scope="col">City</th>
                            <th scope="col">State</th>
                            <th scope="col">Vendor Number</th>
                            <th scope="col">Vendor Name</th>
                            <th scope="col">First hour actual rate</th>
                            <th scope="col">Travel Charge</th>
                            <th scope="col">Flat rate</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendorCare as $key=>$order)
                        <tr class="text-center">
                            <th scope="row">{{$key +1}}</th>
                            <th scope="row">{{$order->order_id}}</th>
                            <th scope="row">{{$order->priority}}</th>
                            <th scope="row">{{@$order->site->customer->company_name}}</th>
                            <th scope="row">{{@$order->site->location}}</th>
                            <th scope="row">{{@$order->site->city}}</th>
                            <th scope="row">{{@$order->site->state}}</th>
                            <th scope="row">{{@$order->tech->technician_id}}</th>
                            <th scope="row">{{@$order->tech->company_name}}</th>
                            <th scope="row">{{$order->fha_rate}}</th>
                            <th scope="row">{{@$order->tech->travel_fee}}</th>
                            <th scope="row">{{@$order->tech->rate}}</th>
                            <th>
                                <div class="dropdown">
                                    <a id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v custom-icon" style="cursor: pointer;"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/customer/edit/site') }}/{{ $order->id }}">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ url('/customer/delete/site') }}/{{ $order->id }}">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </th>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--add Site Modal -->
    <div class="modal fade text-dark" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary d-flex justify-content-between">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Vendor List Care</h5>
                    <button type="button" class="btn-close" id="close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('customer.vendorCareCreate')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <label for="">Priority</label>
                            <select name="priority" class="form-control" id="">
                                <option value="Emergency">Emergency</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <label for="">First hour actual rate</label>
                            <input type="number" class="form-control" name="fha_rate" placeholder="Enter first hour actual rate">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="customer_id"> Site Id:</label>
                                        <select class="form-control" name="site_id" id="site" style="width:100%">
                                            <option value="">Select Site Id</option>
                                            @foreach($sites as $site)
                                            <option value="{{$site->id}}">{{$site->site_id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="customer_id"> Technician Id:</label>
                                        <select class="form-control" name="technician_id" id="tech" style="width:100%">
                                            <option value="">Select Tech Id</option>
                                            @foreach($technicians as $tech)
                                            <option value="{{$tech->id}}">{{$tech->technician_id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn bg-primary btn-block" id="save">Save</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var site = this.value;

        $("#site").select2({
            data: site
        });
        var tech = this.value;

        $("#tech").select2({
            data: tech
        });
    });
</script>
@endsection