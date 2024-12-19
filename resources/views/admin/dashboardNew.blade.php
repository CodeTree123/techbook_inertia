@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
  @include('admin.includeNew.breadcrumb')
  @if(auth()->guard('admin')->user()->role_id != Status::DISPATCH_TEAM)
  <style>
    .card {
      border: 1px solid #e0e0e0;
      transition: transform 0.3s, border-color 0.3s;
    }

    .card-header h3 i {
      margin-right: 10px;
    }

    ol[type="card-circle"] {
      border: 2px solid whitesmoke;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
      transition: border 0.3s, background-color 0.3s;
      margin: 5px;
    }

    ol[type="card-circle"]:hover {
      border: 2px solid green;
      /* background-color: sky; */

    }


    ol[type="card-created"] {
      border: 2px solid whitesmoke;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
      transition: border 0.3s, background-color 0.3s;
      margin: 5px;
    }

    ol[type="card-closed"] {
      border: 2px solid whitesmoke;
      padding: 10px;
      border-radius: 10px;
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
      transition: border 0.3s, background-color 0.3s;
      margin: 5px;
    }


    ol[type="card-created"]:hover {
      border: 2px solid #17a2b8 !important;
    }

    ol[type="card-closed"]:hover {
      border: 2px solid #007bff !important;
      /* background-color: sky; */

    }
  </style>
  <section class="content">
    <div class="container-fluid text-dark">
      <div class="row">
        <div class="col-lg-6 col-6">
          <div class="small-box bg-info">
            <div class="p-4">
              <h1>Vendor</h1>
              <h5 class="text-nowrap"> <i class="ion-funnel
"></i> Assigned : {{$ftechs}}</h5>
              <h5 class="text-nowrap"> <i class="ion-clipboard
"></i> Available : {{$availableTech}}</h5>
            </div>
            <div class="icon">
              <i class="ion ion-bag" style="font-size:150px"></i>
            </div>
            <a href="#" class="small-box-footer">More info</a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-6">
          <div class="small-box bg-primary">
            <div class="p-4">
              <h1>Customer</h1>
              <h5> <i class="ion-help-buoy"></i>
                Total : {{$allCustomerCount}} </h5>
              <h5 class="text-nowrap"> <i class="ion-soup-can"></i> With Order : {{$orderCustomerCount}} </h5>
            </div>
            <div class="icon">
              <i class="ion-person-add" style="font-size: 150px; margin-top:-20px;"></i>
            </div>
            <a href="#" class="small-box-footer">More info </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid text-dark">
      <div class="row">
        <!-- Calendar -->
        <section class="col-lg-6">
          <div class="card bg-gradient-success ">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="far fa-calendar-alt"></i>
                Calendar
              </h3>
              <div class="card-tools">
                <!-- ... (rest of your calendar tools code) ... -->
              </div>
            </div>
            <div class="card-body pt-0">
              <div id="calendar" style="width: 100%"></div>
            </div>
          </div>
        </section>

        <!-- Visitors Map -->
        <section class="col-lg-6 ">
          <div class="card bg-gradient-primary">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fas fa-map-marker-alt mr-1"></i>
                Visitors
              </h3>
              <div class="card-tools">
                <!-- ... (rest of your visitors map tools code) ... -->
              </div>
            </div>
            <div class="card-body">
              <div id="world-map" style="height: 112px; width: 100%;"></div>
            </div>
            <div class="card-footer bg-transparent">
              <div class="row">
                <div class="col-4 text-center">
                  <div id="sparkline-1"></div>
                  <div class="text-white">Visitors</div>
                </div>
                <div class="col-4 text-center">
                  <div id="sparkline-2"></div>
                  <div class="text-white">Online</div>
                </div>
                <div class="col-4 text-center">
                  <div id="sparkline-3"></div>
                  <div class="text-white">Sales</div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>

  <div class="container-fluid text-dark">
    <div class="row">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-header bg-success">
            <h5> Recently Updated <i class="ion-ios-reverse-camera-outline" style="float: right; font-size:25px"></i></h5>
          </div>
          <div class="card-body" style="overflow-y: auto; max-height: 370px; min-height: 370px;">
            @if(count($rUpdated) > 0)
            @foreach($rUpdated as $updated)
            <a href="{{url('customer/order/view')}}/{{ $updated->id}}">
              <ol type="card-circle" class="p-3">
                <p style="display: inline-block; border-radius: 10px; padding: 10px;">
                  <b><i class="ion-android-bulb" style="color: teal; font-size: 25px"></i>
                    <span style="color: #4c4c4c; font-size: 18px">Order:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{$updated->order_id}}</span>
                  <br>
                  <i class="ion-ionic" style="color: teal; font-size: 25px"></i>
                  <b><span style="color: #4c4c4c; font-size: 18px">Site:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{@$updated->site->address_1}}, {{@$updated->site->state}}, {{@$updated->site->zipcode}}</span>
                  <br>
                  <i class="ion-loop" style="color: teal; font-size: 25px"></i>
                  <b><span style="color: #4c4c4c; font-size: 18px">Updated at:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{$updated->created_at}}</span>
                </p>
              </ol>
            </a>
            @endforeach
            @else
            <div class="d-flex justify-content-center align-items-center" style="height: 370px;">
              <div class="">
                <img style="height: 200px;" src="https://cdn-icons-png.flaticon.com/512/7466/7466073.png" alt="">
                <p>No Data Available</p>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-header bg-info">
            <h5> Recently Created <i class="ion-social-dropbox" style="float: right; font-size:25px"></i></h5>
          </div>
          <div class="card-body" style="overflow-y: auto; max-height: 370px; min-height: 370px;">
            @if(count($rCreated) > 0)
            @foreach($rCreated as $create)
            <a href="{{url('customer/order/view')}}/{{ $create->id}}">
              <ol type="card-created" class="p-3">
                <p style="display: inline-block; border-radius: 10px; padding: 10px;">
                  <b><i class="ion-android-bulb" style="color: teal; font-size: 25px"></i>
                    <span style="color: #4c4c4c; font-size: 18px">Order:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{$create->order_id}}</span>
                  <br>
                  <i class="ion-ionic" style="color: teal; font-size: 25px"></i>
                  <b><span style="color: #4c4c4c; font-size: 18px">Site:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{@$create->site->address_1}}, {{@$create->site->state}}, {{@$create->site->zipcode}}</span>
                  <br>
                  <i class="ion-loop" style="color: teal; font-size: 25px"></i>
                  <b><span style="color: #4c4c4c; font-size: 18px">Updated at:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{$create->created_at}}</span>
                </p>
              </ol>
            </a>
            @endforeach
            @else

            <div class="d-flex justify-content-center align-items-center" style="height: 370px;">
              <div>
                <img style="height: 200px;" src="https://cdn-icons-png.flaticon.com/512/7466/7466073.png" alt="">
                <h4>No Data Available</h4>
              </div>
            </div>

            @endif
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-header bg-primary">
            <h5> Recently Closed <i class="ion-android-close" style="float: right; font-size:25px"></i></h5>
          </div>
          <div class="card-body" style="overflow-y: auto; max-height: 370px; min-height: 370px;">
            @if(count($rClosed) > 0)
            @foreach($rClosed as $closed)
            <a href="{{url('customer/order/view')}}/{{ $closed->id}}">
              <ol type="card-closed" class="p-3">
                <p style="display: inline-block; border-radius: 10px; padding: 10px;">
                  <b><i class="ion-android-bulb" style="color: teal; font-size: 25px"></i>
                    <span style="color: #4c4c4c; font-size: 18px">Order:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{$closed->order_id}}</span>
                  <br>
                  <i class="ion-ionic" style="color: teal; font-size: 25px"></i>
                  <b><span style="color: #4c4c4c; font-size: 18px">Site:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{@$closed->site->address_1}}, {{@$closed->site->state}}, {{@$closed->site->zipcode}}</span>
                  <br>
                  <i class="ion-loop" style="color: teal; font-size: 25px"></i>
                  <b><span style="color: #4c4c4c; font-size: 18px">Updated at:</span></b>
                  <span style="color: #4c4c4c; font-size: 15px">{{$closed->created_at}}</span>
                </p>
              </ol>
            </a>
            @endforeach
            @else
            <div class="d-flex justify-content-center align-items-center" style="height: 370px;">
              <div class="">
                <img style="height: 200px;" src="https://cdn-icons-png.flaticon.com/512/7466/7466073.png" alt="">
                <p style="color:#ccc">No Data Available</p>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>

    </div>

  </div>

  @else
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8 ">
        <div class="card">
          <div class="card-header bg-gray">
            <h3>Recent Work Orders</h3>
          </div>
          <div class="card-body">
            <table id="example" class="table">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Address</th>
                  <th>Status</th>
                  <th>Handle</th>
                </tr>
              </thead>
              <tbody>
                @foreach($workOrders as $key=> $order)
                <tr data-id="{{ $order->id }}">
                  <td>{{ $key+1 }}</td>
                  <td><a href="{{url('customer/order/view')}}/{{ $order->id}}">{{ @$order->site->address_1 }}, {{ @$order->site->address_2 }}, {{ @$order->site->city }}, {{ @$order->site->state }}, {{ @$order->site->zipcode }}</a></td>
                  <td><span class="badge badge-success">Open</span></td>
                  <td><a href="{{ route('dispatch.get.distance.page', $order->id) }}" class="btn btn-primary btn-sm" id="dispatch-button">Dispatch</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection