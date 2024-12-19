@extends('admin.layoutsNew.app')

@section('content')
<div class="content-wrapper" style="background-color: white;">
    <!-- Content Header (Page header) -->
    @include('admin.includeNew.breadcrumb')
    <style>
        #example th {
            text-align: center;
            color: black;
            white-space: nowrap;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        #example td {
            text-align: center;
            color: black;
            white-space: nowrap;
        }

        .table-bordered tr {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }

        thead {
            background-color: white;
        }

        thead th {
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        thead th:first-child {
            font-weight: bold;
        }

        label {

            margin-top: 10px;
        }
    </style>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-gray">
                        <h3>{{$pageTitle}}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Tickets Table Section -->
                        @if(in_array($pageTitle, ['Open Ticket', 'Dispatched Ticket', 'Onsite Ticket', 'Invoiced Ticket', 'Closed Ticket', 'ON Hold Ticket']))
                        <table id="example">
                            <thead>
                                <tr>
                                    <th># SL</th>
                                    <th>Vendor Id</th>
                                    <th>Work Order</th>
                                    <th>Customer</th>
                                    @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                    <th>Status</th>
                                    @if(!in_array($pageTitle, ['Closed Ticket', 'ON Hold Ticket']))
                                    <th>Trouble</th>
                                    @endif
                                    @endif
                                    @if($pageTitle === 'Open Ticket')
                                    <th>Action</th>
                                    @endif
                                    @if($pageTitle === 'Closed Ticket')
                                    <th>Invoice</th>
                                    @endif
                                </tr>
                            </thead>


                            @foreach($tickets as $key=>$ticket)
                            <tr class="table-bordered">
                                <td>{{$key + 1}}</td>
                                <td>@if(@$ticket->technician->technician_id)
                                    <a href="{{url('customer/order/view')}}/{{ $ticket->id}}">
                                        <span class="badge badge-light text-primary fs-6">{{@$ticket->technician->technician_id}}</span>
                                    </a>
                                    @else
                                    <h5><span class="badge badge-danger">Pending</span></h5>
                                    @endif
                                </td>
                                <td><a href="{{url('customer/order/view')}}/{{ $ticket->id}}"><span class="badge badge-light text-primary fs-6">{{$ticket->order_id}}</span></a></td>
                                <td><a href="{{url('customer/order/view')}}/{{ $ticket->id}}"><span class="badge badge-light text-primary fs-6">{{@$ticket->customer->company_name}}</span></a></td>
                                @if($pageTitle === 'Open Ticket')
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td><span class="badge badge-success fs-6">Open</span></td>
                                <td>
                                    <div class=" dropdown">
                                        <i class="fas fa-ellipsis-v custom-icon mx-3" style="cursor: pointer;" dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{url('ticket/hold')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to On Hold</span></a>

                                        </div>
                                    </div>
                                </td>
                                @endif
                                <td><a href="{{ route('dispatch.get.distance.page', $ticket->id) }}" class="btn btn-primary btn-sm" id="dispatch-button">Dispatch</a></td>
                                @endif
                                @if(in_array($pageTitle,['Dispatched Ticket']))
                                @if(auth()->guard('admin')->user()->role_id == 1)
                                <td><a href="{{url('ticket/onsite')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to ONSITE</span></a></td>
                                @endif
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td>
                                    <div class=" dropdown">
                                        <i class="fas fa-ellipsis-v custom-icon mx-3" style="cursor: pointer;" dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{url('ticket/hold')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to On Hold</span></a>

                                        </div>
                                    </div>
                                </td>
                                @endif
                                @endif
                                @if($pageTitle === 'Onsite Ticket')
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td><a href="{{url('ticket/invoice')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to Invoiced</span></a></td>
                                @endif
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td>
                                    <div class=" dropdown">
                                        <i class="fas fa-ellipsis-v custom-icon mx-3" style="cursor: pointer;" dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{url('ticket/hold')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to On Hold</span></a>

                                        </div>
                                    </div>
                                </td>
                                @endif
                                @endif
                                @if($pageTitle === 'Invoiced Ticket')
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td><a href="{{url('ticket/close')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to Closed</span></a></td>
                                @endif
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td>
                                    <div class=" dropdown">
                                        <i class="fas fa-ellipsis-v custom-icon mx-3" style="cursor: pointer;" dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{url('ticket/hold')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to On Hold</span></a>

                                        </div>
                                    </div>
                                </td>
                                @endif
                                @endif
                                @if($pageTitle === 'Closed Ticket')
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td><span class="badge badge-danger fs-6">Closed</span></td>
                                <td><a href="{{url('customer/invoice')}}/{{ $ticket->id}}"><span class="badge badge-light text-primary fs-6">{{$ticket->invoice->invoice_number}}</span></a></td>
                                @endif
                                @endif
                                @if($pageTitle === 'ON Hold Ticket')
                                @if(auth()->guard('admin')->user()->role_id == Status::SALES_TEAM)
                                <td><a href="{{url('ticket/onsite')}}/{{ $ticket->id}}" class="btn btn-success"><span class="badge badge-warning text-primary fs-6">Change to Onsite</span></a></td>
                                @endif
                                @endif
                            </tr>
                            @endforeach
                        </table>

                        @endif
                    </div>
                    @if ($tickets->hasPages())
                    <div class="card-footer py-4">
                        <p class="text-italic">Click below to see next page</p> @php echo paginateLinks($tickets) @endphp
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<p class="font-weight-light p-2 m-2">Do Search by Work Order Id :</p>
<x-search-form dateSearch='yes' />
@endpush