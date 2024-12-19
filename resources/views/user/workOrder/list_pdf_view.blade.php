@extends('layouts.app')
@section('content')
<style>
    .ma:hover {
        background: #9ACFF5;
    }

    .ma {
        background: white;
    }

    .action-dropdown .dropdown-menu {
        display: none;
    }

    #data-table th,
    #data-table td {
        text-align: center;
        vertical-align: middle;
    }

    .form-check {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-smaller {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.2;
        border-radius: 0;
    }

    .btn-smaller2 {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    #closeout-notes-table th,
    #table1 td,
    #techSupport-notes-table th,
    #table2 td,
    #billing-notes-table th,
    #table3 td,
    #dispatch-notes-table th,
    #table4 td,
    #general-notes-table th,
    #table5 td {
        text-align: center;
    }

    .tab-container {
        border: 1px solid #ddd;
        padding: 5px;
        max-width: 1200px;
        margin: auto;
        display: flex;
        justify-content: space-between;
    }

    .tab-item {
        text-align: center;
        padding: 10px;
        border-right: 1px solid #ddd;
        cursor: pointer;
        width: 20%;
    }

    .tab-item:last-child {
        border-right: none;
    }

    .tab-item.active {
        background-color: #AFE1AF;
        font-weight: bold;
    }

    .tab-container {
        border-radius: 14px;
        max-width: 1200px;
        margin: auto;
        display: flex;
        justify-content: space-between;
    }

    .dropdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Work Order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteButton">Delete</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-2" id="allRecord">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #AFE1AF;">

                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="text-white">Work Orders</h3>
                        </div>
                        <div class="col-md-8 d-flex justify-content-end">
                            <a href="{{ route('user.work.order.stage.new') }}" class="btn ma btn-sm m-1 d-flex justify-content-center align-items-center"><i class="fa-solid fa-filter"></i>New <span class="badge bg-dark my-1">{{$new}}</span></a>
                            <a href="{{ route('user.work.order.stage.needDispatch') }}"
                                class="btn ma btn-sm m-1 d-flex justify-content-center align-items-center"><i class="fa-solid fa-filter"></i>Need Dispatch <span class="badge bg-dark my-1 d-flex justify-content-center align-items-center">{{$needDispatch}}</span></a>
                            <a href="{{ route('user.work.order.stage.dispatched') }}"
                                class="btn ma btn-sm m-1 d-flex justify-content-center align-items-center"><i class="fa-solid fa-filter"></i>Dispatched <span class="badge bg-dark my-1">{{$dispatched}}</span></a>
                            <a href="{{ route('user.work.order.stage.closed') }}"
                                class="btn ma btn-sm m-1 d-flex justify-content-center align-items-center"><i class="fa-solid fa-filter"></i>Closed <span class="badge bg-dark my-1">{{$closed}}</span></a>
                            <a href="{{ route('user.work.order.stage.billing') }}"
                                class="btn ma btn-sm m-1 d-flex justify-content-center align-items-center"><i class="fa-solid fa-filter"></i>Billing <span class="badge bg-dark my-1">{{$billing}}</span></a>
                            <div class="dropdown">
                                <a class="btn ma m-1 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-filter"></i>WO.Status
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" data-bs-auto-close="true">
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.pending') }}">Pending<span class="badge bg-dark my-1">{{$pending}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.contacted') }}">Contacted<span class="badge bg-dark my-1">{{$contacted}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.confirmed') }}">Confirmed<span class="badge bg-dark my-1">{{$confirmed}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.atRisk') }}">At Risk<span class="badge bg-dark my-1">{{$atRisk}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.delayed') }}">Delayed<span class="badge bg-dark my-1">{{$delayed}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.onHold') }}">On Hold<span class="badge bg-dark my-1">{{$onHold}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.enRoute') }}">En Route<span class="badge bg-dark my-1">{{$enRoute}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.checkedIn') }}">Checked In<span class="badge bg-dark my-1">{{$checkedIn}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.checkedOut') }}">Checked Out<span class="badge bg-dark my-1">{{$checkedOut}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.needsApproval') }}">Needs Approval<span class="badge bg-dark my-1">{{$needsApproval}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.issue') }}">Issue<span class="badge bg-dark my-1">{{$issue}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.approved') }}">Approved<span class="badge bg-dark my-1">{{$approved}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.invoiced') }}">Invoiced<span class="badge bg-dark my-1">{{$invoiced}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.pastDue') }}">Past Due<span class="badge bg-dark my-1">{{$pastDue}}</span></a></li>
                                    <li><a style="background-color: #AFE1AF;" class="dropdown-item m-1" href="{{ route('user.work.order.view.paid') }}">Paid<span class="badge bg-dark my-1">{{$paid}}</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"> Work Order ID </th>
                                <th scope="col"> Created Time </th>
                                <th scope="col"> Preview </th>
                                <th scope="col">Customer</th>
                                <th scope="col"> Stage </th>
                                <th scope="col">Status</th>
                                <th scope="col">PDf</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($work as $w)
                            <tr class="text-center" id="filerPageId" data-id="{{ $w->id }}">
                                <td><a href="{{route('user.work.order.view.inertia',$w->id)}}"> {{ $w->order_id }} </a></td>
                                <td>{{ $w->created_at->diffForHumans() }}</td>
                                <td><a class="btn ma" href="{{route('user.work.order.view.layout', $w->id)}}"><i class="fa-solid fa-display"></i></a></td>
                                @if ($w->slug == null)
                                <td>
                                    <button class="btn ma btn-sm"><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                </td>
                                @else
                                <td>{{ $w->customer->company_name }}</td>
                                @endif
                                <td>
                                    <div class="dropdown">
                                        <a class="btn ma btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item" href="{{ route('user.order.stage.change', ['id' => $w->id, 'value' => 1]) }}">New</a></li>
                                            <li><a class="dropdown-item" href="{{ route('user.order.stage.change', ['id' => $w->id, 'value' => 2]) }}">Need to Dispatch</a></li>
                                            <li><a class="dropdown-item" href="{{ route('user.order.stage.change', ['id' => $w->id, 'value' => 3]) }}">Dispatched</a></li>
                                            <li><a class="dropdown-item" href="{{ route('user.order.stage.change', ['id' => $w->id, 'value' => 4]) }}">Closed</a></li>
                                            <li><a class="dropdown-item" href="{{ route('user.order.stage.change', ['id' => $w->id, 'value' => 5]) }}">Billing</a></li>
                                        </ul>
                                    </div>
                                </td>
                                @if ($w->status == 1)
                                <td><span class="badge bg-success">Pending</span></td>
                                @elseif($w->status == 2)
                                <td><span class="badge bg-info text-dark">Contacted</span></td>
                                @elseif($w->status == 3)
                                <td><span class="badge bg-light text-dark">Confirmed</span></td>
                                @elseif($w->status == 4)
                                <td><span class="badge bg-dark">At Risk</span></td>
                                @elseif($w->status == 5)
                                <td><span class="badge bg-primary">Delayed</span></td>
                                @elseif($w->status == 6)
                                <td><span class="badge bg-warning text-dark">On Hold</span></td>
                                @elseif($w->status == 7)
                                <td><span class="badge bg-danger">En Route</span></td>
                                @elseif($w->status == 8)
                                <td><span class="badge bg-secondary">Checked In</span></td>
                                @elseif($w->status == 9)
                                <td><span class="badge bg-secondary">Checked Out</span></td>
                                @elseif($w->status == 10)
                                <td><span class="badge bg-secondary">Needs Approval</span></td>
                                @elseif($w->status == 11)
                                <td><span class="badge bg-danger">Issue</span></td>
                                @elseif($w->status == 12)
                                <td><span class="badge bg-success">Approved</span></td>
                                @elseif($w->status == 13)
                                <td><span class="badge bg-success">Invoiced</span></td>
                                @elseif($w->status == 14)
                                <td><span class="badge bg-warning text-dark">Past Due</span></td>
                                @elseif($w->status == 15)
                                <td><span class="badge bg-success">Paid</span></td>
                                @endif
                                <td>
                                    <button class="btn ma btn-sm"
                                        onclick="window.location.href='{{ url('pdf/work/order/view/') }}/{{ $w->id }}'"><i
                                            class="fas fa-eye"></i></button>
                                </td>
                                <td class="custom-action-dropdown">
                                    <div class="custom-dropdown dropdown">
                                        <i class="fas fa-ellipsis-v custom-action-btn" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <ul class="dropdown-menu custom-dropdown-menu">
                                            <li>
                                                <a class="dropdown-item custom-dropdown-item"
                                                    href="{{ url('pdf/work/order/download/') }}/{{ $w->id }}">
                                                    <i class="fas fa-file-pdf"></i> Download
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item custom-dropdown-item custom-delete-button"
                                                    href="#" data-id="{{ $w->id }}">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($work->hasPages())
                <div class="card-footer py-4">
                    <p class="text-italic">Click below to see next page</p> @php echo paginateLinks($work) @endphp
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="tab-container d-none d-flex justify-content-center" id="tab-stage">
    <div class="tab-item " id="newTab">NEW</div>
    <div class="tab-item " id="needDispatchTab">NEED DISPATCH</div>
    <div class="tab-item " id="dispatchTab">DISPATCHED</div>
    <div class="tab-item " id="completeTab">CLOSED</div>
    <div class="tab-item " id="billingTab">BILLING</div>
</div>
<div class="container-fluid navigation d-none" style="margin-top: 15px; margin-bottom: 60px; z-index: 9; position: relative" id="defualtWorkOrder">
    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist" style="background-color:#AFE1AF;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active mt-4" id="work-order-tab" data-bs-toggle="tab" data-bs-target="#home"
                type="button" role="tab" aria-controls="home" aria-selected="true">
                <i class="fa-brands fa-first-order" style="color: green; margin-bottom:15px"></i> WO.Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link mt-4" id="notes-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true"><i class="fa-regular fa-note-sticky"
                    style="color: green; margin-bottom:15px;"></i> WO.History</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link mt-4" id="site-history-tab" data-bs-toggle="tab" data-bs-target="#home"
                type="button" role="tab" aria-controls="home" aria-selected="true"><i
                    class="fa-solid fa-stethoscope" style="color: green;margin-bottom:15px;"></i> Site
                History</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link mt-4" id="parts" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-cogs"
                    style="color: green;margin-bottom:15px;"></i> Parts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link mt-4" id="ticket" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="false"><i class="fas fa-ticket-alt"
                    style="color: green;margin-bottom:15px;"></i> Support Tickets</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link mt-4" id="fieldTech" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-user-cog"
                    style="color: green;margin-bottom:15px;"></i> Field Tech</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link mt-4" id="check_out" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-sign-in-alt"
                    style="color: green;margin-bottom:15px;"></i> Check-In/Out</button>
        </li>
        <li><button class="nav-link mt-4" id="woViewButton"><i class="fa fa-eye" aria-hidden="true"></i></button></li>
        <li>
            <button class="nav-link mt-4" id="filerPageOrderIdOut"><i class="fa fa-window-close" aria-hidden="true"></i></button>
        </li>
    </ul>

    <div class="row justify-content-center d-none" id="workOrderSearchForm">
        <div class="col-md-12">
            @if (auth()->user()->kv == 0)
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">@lang('KYC Verification required')</h4>
                <hr>
                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic officia quod natus,
                    non dicta perspiciatis, quae repellendus ea illum aut debitis sint amet? Ratione voluptates
                    beatae numquam. <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
            </div>
            @elseif(auth()->user()->kv == 2)
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">@lang('KYC Verification pending')</h4>
                <hr>
                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic officia quod natus,
                    non dicta perspiciatis, quae repellendus ea illum aut debitis sint amet? Ratione voluptates
                    beatae numquam. <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
            </div>
            @endif
            <div class="card shadow whole-card " style="border-radius:0px; border-top:none">
                <form id="defaultWO" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-end align-items-center">
                            <button class="btn" style="background-color:#AFE1AF;" type="submit"><b>Save</b></button>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h6><i class="fas fa-magnifying-glass" style="font-size: 13px"></i>&nbsp;Work Order
                                </h6>
                                <input name="order_id" type="text" class="form-control" id="workOrderSearchInput"
                                    placeholder="OrderId/CompanyName/Zipcode" autocomplete="off">
                                <input type="hidden" name="workOrderId" id="workOrderId">
                                <span id="dashboardOrderIdErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col">
                                <h6>Requested Date</h6>
                                <input name="open_date" type="text" class="form-control" value=""
                                    id="dashboardReqDate" autocomplete="off">
                            </div>
                            <div class="col">
                                <h6>Requested By</h6>
                                <input name="requested_by" type="text" class="form-control" id="dashboardReqBy">
                            </div>
                            <div class="col">
                                <h6>Request Type</h6>
                                <select name="request_type" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example" id="dashboardEmailPhoneSelect">
                                    <option value="Email">Email</option>
                                    <option value="Phone">Phone</option>
                                </select>
                            </div>
                            <div class="col">
                                <h6>Priority</h6>
                                <select name="priority" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example" id="dashboardPrioritySelect">
                                    <option value="1">P1</option>
                                    <option value="2">P2</option>
                                    <option value="3">P3</option>
                                    <option value="3">P4</option>
                                    <option value="3">P5</option>
                                </select>
                            </div>
                            <div class="col">
                                <h6>Complete By</h6>
                                <input name="complete_by" type="text" id="dashboardCompletedBy"
                                    class="form-control" autocomplete="off">
                            </div>
                            <div class="col">
                                <h6>Purchase Order</h6>
                                <input name="p_o" type="text" id="dashboardpO"
                                    class="form-control" autocomplete="off">
                            </div>
                            <div class="col">
                                <h6>Stage</h6>
                                <select name="stage" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example" id="dashboardWorkOrderStage">
                                    <option value="1">New</option>
                                    <option value="2">Needs Dispatch</option>
                                    <option value="3">Dispatched</option>
                                    <option value="4">Closed</option>
                                    <option value="5">Billing</option>
                                </select>
                            </div>
                            <div class="col">
                                <h6>Status</h6>
                                <select name="status" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example" id="dashboardWorkOrderStatus">
                                    <option value="1">Pending</option>
                                    <option value="2">Contacted</option>
                                    <option value="3">Confirmed</option>
                                    <option value="4">At Risk</option>
                                    <option value="5">Delayed</option>
                                    <option value="6">On Hold</option>
                                    <option value="7">En Route</option>
                                    <option value="8">Checked In</option>
                                    <option value="9">Checked Out</option>
                                    <option value="10">Needs Approval</option>
                                    <option value="11">Issue</option>
                                    <option value="12">Approved</option>
                                    <option value="13">Invoiced</option>
                                    <option value="14">Past Due</option>
                                    <option value="15">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>Project Manager</h6>
                                <input type="text" class="form-control" id="dashboardEmployeeId" autocomplete="off">
                                <input type="hidden" name="em_id" id="dashboardEmployeeIdInput">
                                <span id="dashboardEmployeeIdInputErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col-md-6">
                                <h6>Sales Person</h6>
                                <input type="text" class="form-control" id="dashboardSp">
                            </div>
                            <div class="col-md-6">
                                <h5><b><i class="fas fa-magnifying-glass"
                                            style="font-size: 16px"></i>&nbsp;Customer</b></h5>
                                <input type="text" class="form-control" id="dashboardCustomerId"
                                    placeholder="Search with Customer Name / Customer Id / Zipcode"
                                    autocomplete="off">
                                <input type="hidden" name="customer_id" id="dashboardCustomerIdInput">
                                <span id="dashboardCustomerIdErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col-md-6">
                                <h5><b><i class="fas fa-magnifying-glass" style="font-size: 16px"></i>&nbsp;Site</b>
                                </h5>
                                <input type="text" class="form-control" id="dashboardSiteId" autocomplete="off"
                                    placeholder="Search with Location Name / Site Id / Zipcode">
                                <input type="hidden" name="site_id" id="dashboardSiteIdInput">
                                <span id="dashboardSiteIdErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col-md-6">
                                <h6>Address</h6>
                                <input type="text" class="form-control" id="dashboardCustomerAddress" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Address</h6>
                                <input type="text" class="form-control" id="dashboardSiteAddress" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>City</h6>
                                <input type="text" class="form-control" id="dashboardCustomerCity" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>City</h6>
                                <input type="text" class="form-control" id="dashboardSiteCity" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>State</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="dashboardCustomerState" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>State</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="dashboardSiteState" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Zip Code</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="dashboardCustomerZipcode" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Zip Code</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="dashboardSiteZipcode" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Phone</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="dashboardCustomerPhone" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Phone</h6>
                                <input type="text" class="form-control" style="width: 200px;" readonly>
                            </div>
                            <div class="col-md-2 ">
                                <h6>Site Contact Name</h6>
                                <input name="site_contact_name" type="text" class="form-control"
                                    id="dashboardSiteContact">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Site Contact Phone</h6>
                                <input name="site_contact_phone" type="text" class="form-control"
                                    id="dashboardSiteContactPhone">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Site Hours Of Operation</h6>
                                <input name="h_operation" type="text" class="form-control"
                                    id="dashboardSiteHoursOp">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Scheduled Date</h6>
                                <input name="on_site_by" type="text" class="form-control" id="dashboardOnsiteBy"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Scheduled Time</h6>
                                <input name="scheduled_time" type="text" class="form-control" id="dashboardscheduledTime"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <h6>Number of Techs Required</h6>
                                <input name="num_tech_required" type="text" class="form-control"
                                    id="dashboardNumOfTech">
                            </div>
                            <div class="col-md-12 ">
                                <div class="d-flex justify-content-end align-items-center my-2">
                                    <button class="btn" style="background-color:#AFE1AF;" type="submit"><b>Save</b></button>
                                </div>
                                <h6>Scope Of Work</h6>
                                <textarea name="scope_work" class="form-control textEditor" rows="10" id="dashboardScopeOfWork"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end align-items-center my-2">
                                    <button class="btn" style="background-color:#AFE1AF;" type="submit"><b>Save</b></button>
                                </div>
                                <h6>Deliverables</h6>
                                <textarea name="deliverables" class="form-control textEditor" id="dashboardDeliverables"></textarea>
                            </div>
                            <div class="col-md-12 ">
                                <div class="d-flex justify-content-end align-items-center my-2">
                                    <button class="btn" style="background-color:#AFE1AF;" type="submit"><b>Save</b></button>
                                </div>
                                <h6>Tools Required</h6>
                                <textarea name="r_tools" class="form-control textEditor" id="dashboardToolsRequired"></textarea>
                            </div>
                            <div class="col-md-12 ">
                                <div class="d-flex justify-content-end align-items-center my-2">
                                    <button class="btn" style="background-color:#AFE1AF;" type="submit"><b>Save</b></button>
                                </div>
                                <h6>Dispatch Instructions</h6>
                                <textarea name="instruction" class="form-control textEditor" id="dashboardDispatchIns"></textarea>
                            </div>
                            <div class="col-md-12">
                                <h3>Insert Image</h3>
                                <input type="file" id="pictures" name="pictures[]" class="form-control" multiple onchange="handleFiles(this.files)">
                            </div>

                            <div id="imagePreviewContainer" class="col-md-12 mt-3">
                                <div id="imagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                            </div>


                            <div class="col-12">
                                <div class="dropdown mt-3">
                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        New Notes
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" id="general">General Note</a></li>
                                        <li><a class="dropdown-item" id="dispatch">Dispatch Note</a></li>
                                        <li><a class="dropdown-item" id="bill">Billing Note</a></li>
                                        <li><a class="dropdown-item" id="tech">Tech Support Note</a></li>
                                        <li><a class="dropdown-item" id="close">Close Out Note</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-12 d-none" id="generalNote">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">General Notes:</h6>
                                    <button type="submit" class="btn btn-primary btn-smaller2">Save</button>
                                </div>
                                <textarea name="general_notes" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 d-none" id="closeOut">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Closeout Notes:</h6>
                                    <button class="btn btn-primary btn-smaller2" type="submit">Save</button>
                                </div>
                                <textarea name="close_out_notes" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 d-none" id="dNote">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Dispatch Note:</h6>
                                    <button class="btn btn-primary btn-smaller2" type="submit">Save</button>
                                </div>
                                <textarea name="dispatch_notes" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 d-none" id="bNote">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Billing Note:</h6>
                                    <button class="btn btn-primary btn-smaller2" type="submit">Save</button>
                                </div>
                                <textarea name="billing_notes" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 d-none" id="tNote">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Tech Support Note:</h6>
                                    <button class="btn btn-primary btn-smaller2" type="submit">Save</button>
                                </div>
                                <textarea name="tech_support_notes" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-3">
                                <button class="btn w-100 mt-3" style="background-color:#AFE1AF;" type="submit" id="orderSubmitButton">
                                    <i class="d-none fa fa-spinner fa-spin" style="font-size:16px"></i>
                                    <span class="button-text"><i class="fa fa-paper-plane" aria-hidden="true"></i>Submit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- new work order  -->
<div class="container-fluid" style="margin-top: 50px;">
    <div class="row justify-content-center d-none" id="workOrderCreateForm">
        <div class="col-md-12">
            @if (auth()->user()->kv == 0)
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">@lang('KYC Verification required')</h4>
                <hr>
                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic officia quod natus,
                    non dicta perspiciatis, quae repellendus ea illum aut debitis sint amet? Ratione voluptates
                    beatae numquam. <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
            </div>
            @elseif(auth()->user()->kv == 2)
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">@lang('KYC Verification pending')</h4>
                <hr>
                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic officia quod natus,
                    non dicta perspiciatis, quae repellendus ea illum aut debitis sint amet? Ratione voluptates
                    beatae numquam. <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
            </div>
            @endif
            <div class="card shadow whole-card p-3">
                <form id="WOFORM">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6>Requested Date</h6>
                                <input name="open_date" type="text" class="form-control" value=""
                                    id="workOrderCreateReqDate" readonly autocomplete="off">
                            </div>
                            <div class="col">
                                <h6>Requested By</h6>
                                <input name="requested_by" type="text" class="form-control">
                            </div>
                            <div class="col">
                                <h6>Request Type</h6>
                                <select class="form-select form-select-sm" name="request_type"
                                    aria-label=".form-select-sm example" id="dashboardEmailPhoneSelect">
                                    <option value="Email">Email</option>
                                    <option value="Phone">Phone</option>
                                </select>
                            </div>
                            <div class="col">
                                <h6>Priority</h6>
                                <select class="form-select form-select-sm" name="priority"
                                    aria-label=".form-select-sm example">
                                    <option value="1">P1</option>
                                    <option value="2">P2</option>
                                    <option value="3">P3</option>
                                    <option value="3">P4</option>
                                    <option value="3">P5</option>
                                </select>
                            </div>
                            <div class="col">
                                <h6>Complete By</h6>
                                <input name="complete_by" type="text" id="completedByCreateForm"
                                    class="form-control" autocomplete="off">
                            </div>
                            <div class="col">
                                <h6>Purchase Order</h6>
                                <input name="p_o" type="text" id="pOCreateForm"
                                    class="form-control" autocomplete="off">
                            </div>
                            <div class="col">
                                <h6>Status</h6>
                                <select name="status" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example" id="workOrderCreateStatus">
                                    <option value="1">Pending</option>
                                    <option value="2">Contacted</option>
                                    <option value="3">Confirmed</option>
                                    <option value="4">At Risk</option>
                                    <option value="5">Delayed</option>
                                    <option value="6">On Hold</option>
                                    <option value="7">En Route</option>
                                    <option value="8">Checked In</option>
                                    <option value="9">Checked Out</option>
                                    <option value="10">Needs Approval</option>
                                    <option value="11">Issue</option>
                                    <option value="12">Approved</option>
                                    <option value="13">Invoiced</option>
                                    <option value="14">Past Due</option>
                                    <option value="15">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>Project Manager</h6>
                                <input type="text" class="form-control" id="createFormEmployeeId" autocomplete="off">
                                <input type="hidden" name="em_id" id="createFormEmployeeIdInput">
                                <span id="createFormEmployeeIdInputErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col-md-6">
                                <h6>Sales Person</h6>
                                <input type="text" class="form-control" id="customerSpCreateForm">
                            </div>
                            <div class="col-md-6">
                                <h5><b><i class="fas fa-magnifying-glass"
                                            style="font-size: 16px"></i>&nbsp;Customer</b></h5>
                                <input type="text" class="form-control" id="CustomerIdCreateForm"
                                    autocomplete="off"
                                    placeholder="Search with Customer Name / Customer Id / Zipcode">
                                <input type="hidden" name="customer_id" id="customer_idCreateForm">
                                <span id="createFormCusIdErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col-md-6">
                                <h5><b><i class="fas fa-magnifying-glass" style="font-size: 16px"></i>&nbsp;Site</b>
                                </h5>
                                <input name="site_id" type="text" class="form-control" id="siteIdCreateForm"
                                    autocomplete="off" placeholder="Search with Location Name / Site Id / Zipcode">
                                <input type="hidden" name="site_id" id="site_idCreateForm">
                                <span id="siteIdCreateFormErrors" style="font-size: 14px; color:red;"></span>
                            </div>
                            <div class="col-md-6">
                                <h6>Address</h6>
                                <input type="text" class="form-control" id="customerAddressCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Address</h6>
                                <input type="text" class="form-control" id="siteAddressCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>City</h6>
                                <input type="text" class="form-control" id="customerCityCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>City</h6>
                                <input type="text" class="form-control" id="siteCityCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>State</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="customerStateCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>State</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="siteStateCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Zip Code</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="customerZipcodeCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Zip Code</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="siteZipcodeCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Phone</h6>
                                <input type="text" class="form-control" style="width: 200px;"
                                    id="customerPhoneCreateForm" readonly>
                            </div>
                            <div class="col-md-6">
                                <h6>Phone</h6>
                                <input type="text" class="form-control" style="width: 200px;" readonly>
                            </div>
                            <div class="col-md-3 ">
                                <h6>Site Contact</h6>
                                <input name="site_contact_name" type="text" class="form-control"
                                    id="dashboardSiteContact">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Site Contact Phone</h6>
                                <input name="site_contact_phone" type="text" class="form-control"
                                    id="dashboardSiteContactPhone">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Site Hours Of Operation</h6>
                                <input name="h_operation" type="text" class="form-control"
                                    id="dashboardSiteHoursOp">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Scheduled Date</h6>
                                <input name="on_site_by" type="text" class="form-control" id="woCreateOnsiteBy"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2 ">
                                <h6>Scheduled Time</h6>
                                <input name="scheduled_time" type="text" class="form-control" id="woCreatescheduledTime"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-3 ">
                                <h6>Number of Techs Required</h6>
                                <input name="num_tech_required" type="text" class="form-control"
                                    id="dashboardNumOfTech">
                            </div>
                            <div class="col-12 ">
                                <h6>Scope Of Work</h6>
                                <textarea name="scope_work" class="form-control textEditor" rows="10" id="createFormScopeOfWork"></textarea>
                            </div>
                            <div class="col-md-12 ">
                                <h6>Deliverables</h6>
                                <textarea name="deliverables" class="form-control textEditor" id="createFormDeliverables"></textarea>
                            </div>
                            <div class="col-md-12 ">
                                <h6>Tools Required</h6>
                                <textarea name="r_tools" class="form-control textEditor" id="createFormToolsRequired"></textarea>
                            </div>
                            <div class="col-md-12 ">
                                <h6>Dispatch Instructions</h6>
                                <textarea name="instruction" class="form-control textEditor" id="createFormDispatchIns"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 mt-3" type="submit" id="orderSubmitButtonN">
                                    <i class="d-none fa fa-spinner fa-spin" style="font-size:16px"></i>
                                    <span class="button-text2">Submit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>
<div class="container-fluid d-none" id="notes-container">
    <div class="card shadow" style="margin-top:-60px; border-top:none; border-radius:0px">
        <div class="card-body mt-4 p-4">
            <div class="row">
                <div class="form-group col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>General Notes</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="general-notes-table" style="width: 100%">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>#</th>
                                        <th>General Notes</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dispatch Notes</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="dispatch-notes-table" style="width: 100%">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>#</th>
                                        <th>Dispatch Notes</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12 mt-4 ">
                    <div class="card">
                        <div class="card-header">
                            <h5>Billing Notes</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="billing-notes-table" style="width: 100%">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>#</th>
                                        <th>Billing Notes</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12  mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tech-Support Notes</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="techSupport-notes-table" style="width: 100%">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>#</th>
                                        <th>Tech-Support Notes</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12  mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Closeout Notes</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="closeout-notes-table" style="width: 100%">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>#</th>
                                        <th>Closeout Notes</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid d-none" id="site_history_view">
    <div class="row" style="margin-top:-60px">
        <div class="col-md-12">
            <div class="card" style="border-top: none; border-radius:0px;  ">
                <div class="card-header">
                    <h3>Site History</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto d-none" id="siteHistoryTabDiv">
                            <table class="table table-bordered text-left">
                                <tr>
                                    <td id="siteHCompany"></td>
                                    <td id="siteHLocation"></td>
                                </tr>
                                <tr>
                                    <td id="siteHState"></td>
                                    <td id="siteHZipcode"></td>
                                </tr>
                                <tr>
                                    <td id="siteHCity"></td>
                                    <td id="siteHAddress"></td>
                                </tr>
                                <tr>
                                    <td id="siteHtech"></td>
                                    <td id="siteHname"></td>
                                </tr>
                                <tr>
                                    <td id="siteHphone"></td>
                                    <td id="siteHwork"></td>
                                </tr>
                                <tr>
                                    <td id="siteHwcomplete"></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <h6 id="siteHistoryMessage"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid d-none" id="parts_view">
    <div class="card" style="border-top: none; border-radius:0px; margin-top:-60px ">
        <div class="card-header">
            <h3>Parts</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Select Parts From Inventory</h5>
                        </div>
                        <form id="parts-form">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <div class="form-label">
                                            <h6>Search Parts</h6>
                                        </div>
                                        <input type="text" class="form-control" id="parts-search-parts">
                                        <input type="hidden" id="parts-customer-id">
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-label">
                                            <h6>Item Name</h6>
                                        </div>
                                        <input type="text" class="form-control" id="parts-item-name" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-label">
                                            <h6>Quantity Left</h6>
                                        </div>
                                        <input type="text" class="form-control" id="parts-quantity" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-label">
                                            <h6>Quantity Need</h6>
                                        </div>
                                        <input type="text" class="form-control" id="parts-quantity-need">
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-label">
                                            <h6>Unit Price</h6>
                                        </div>
                                        <input type="text" class="form-control" id="parts-unit-price" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-label">
                                            <h6>Total Price</h6>
                                        </div>
                                        <input type="text" class="form-control" id="parts-total-price" readonly>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="button" id="submit-inventory"
                                            class="btn btn-primary btn-sm mt-2 w-100"><i
                                                class="fas fa-sign-out-alt "></i> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Customer Inventory Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>Customer Id</th>
                                        <th>Company Name</th>
                                        <th>Available Parts</th>
                                        <th>Total Parts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td id="partsCusIdTd"></td>
                                        <td id="partsCusNameTd"></td>
                                        <td id="partsCusAvailPartsTd"></td>
                                        <td id="partsCusTotalPartsTd"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <label style="font-weight: 600">Required Tools</label>
                                <hr>
                                <h5 id="r_tools"></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid d-none" id="fieldTech_view" style="margin-top:-60px">
    <div class="card" style="border-top:none; border-radius:0px">
        <div class="card-header">
            <h3>Field Technician</h3>
            <span style="float:right">
                <button type="button" class="btn btn-success" id="findClosestTechBtn">
                    <i class="fa fa-magnifying-glass" style="font-size: 13px;"></i>&nbsp;Find Tech
                </button>
            </span>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mx-auto d-none" id="fTechTabDiv">
                    <h5>Assigned Technician Details</h5>
                    <table class="table table-bordered text-left">
                        <tr>
                            <td id="ftech_company"></td>
                            <td id="ftech_country"></td>
                        </tr>
                        <tr>
                            <td id="ftech_id"></td>
                            <td id="ftech_city"></td>
                        </tr>
                        <tr>
                            <td id="ftech_email"></td>
                            <td id="ftech_state"></td>
                        </tr>
                        <tr>
                            <td id="ftech_address"></td>
                            <td id="ftech_zipcode"></td>
                        </tr>
                    </table>
                </div>
                <h6 id="assignedTechMessage"></h6>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid d-none" id="ticket_view" style="margin-top:-60px">
    <div class="card" style="border-top: none; border-radius:0px; ">
        <div class="card-header">
            <h3>Support Ticket</h3>
        </div>
        <div class="card-body" style="border-top: none; border-radius:0px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tech Support Note Lists</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="sub_ticket_table" style="width: 100%">
                                <thead class="text-nowrap">
                                    <tr>
                                        <th>#</th>
                                        <th>Tech Support Note</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add Notes</h5>
                        </div>
                        <div class="card-body">
                            <form id="create_sub_ticket">
                                <input type="hidden" name="work_order_id" id="w_id">
                                <div class="form-group col-12">
                                    <div class="form-label"><strong>Tech Support Note :</strong></div>
                                    <textarea name="tech_support_note" id="" cols="80" rows="4" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-12 d-flex justify-content-center">
                                    <button class="btn btn-primary btn-sm my-2" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid d-none" id="check_out_view" style="margin-top:-60px">
    <div class="card shadow-lg" style="border-top:none; border-radius:0px ">
        <div class="card-header d-flex">
            <h3 id="Header_time_zone"></h3>
            <h3>Check In/Out</h3>
        </div>
        <div class="card-body">
            @php
            use Carbon\Carbon;
            $date = date('m/d/y');
            $time = Carbon::now()->format('H:i:s');
            @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" id="create_check_in">
                                <input type="hidden" id="check_in_w_id" name="work_order_id">
                                <input type="hidden" id="time_zone" name="time_zone">
                                <div class="form-group">
                                    <div class="form-label">Date</div>
                                    <input type="text" class="form-control" name="date"
                                        value="{{ $date }}">
                                    <span class="text-danger" id="check-in-out-date-error"></span>
                                    <div class="form-label">Company Name</div>
                                    <input type="text" class="form-control" name="company_name"
                                        id="Check_in_ftech_company" readonly>
                                    <span class="text-danger" id="check-in-out-company_name-error"></span>
                                    <div class="form-label">Technician Name</div>
                                    <input type="text" class="form-control" name="tech_name">
                                    <span class="text-danger" id="check-in-out-tech_name-error"></span>
                                    <div class="form-label">Check In</div>
                                    <input type="time" class="form-control" name="check_in"
                                        value="{{ $time }}">
                                    <span class="text-danger" id="check-in-out-check_in-error"></span>
                                    <button type="submit" class="btn btn-primary mt-4 w-100">
                                        <i class="fa-solid fa-right-to-bracket"></i> In
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <table class="table table-bordered" id="checkInOutTable" style="width: 100%">
                        <thead class="text-nowrap">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Company Name</th>
                                <th>Tech name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Tot. Hours</th>
                                <th>Timezone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                @include('user.check-in-out-modal.edit')
                @include('user.check-in-out-modal.round_trip')
            </div>
        </div>
    </div>
</div>

<div class="container-fluid d-none" id="tech_distance_view" style="margin-top:-60px">
    @include('user.distanceMeasureModal.assign')
    @include('user.distanceMeasureModal.contact')
    <div class="card" style="border-top: none; border-radius:0px; margin-top: 40px;">
        {{-- <div class="card-header">
                <h3>Measure Technician Distance</h3>
            </div> --}}
        <div class="card-body">
            <div class="d-none" id="loader">
                <h6 class="text-dark"><strong>Please wait for the responses from google</strong></h6>
                <div class="spinner-grow text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="d-none" id="removable-div">
                <div class="table-responsive">
                    <table class="table table-bordered text-dark" id="data-table">
                        <thead class="text-nowrap">
                            <tr>
                                <th>Assign</th>
                                <th>Technician ID</th>
                                <th>Company Name</th>
                                <th>Status</th>
                                <th>Skill Sets</th>
                                <th>Rate</th>
                                <th>Travel Fee</th>
                                <th>Preferred?</th>
                                <th>Distance</th>
                                <th>Duration</th>
                                <th>Within Radius ?</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" class="text-center"></tbody>
                    </table>
                </div>
                <div style="text-align:center;">
                    <button type="button" class="btn btn-success my-1" id="btn-find-more">Next</button>
                </div>
            </div>
            <div class="d-none" id="confirmation-div">
                <div class="card">
                    <div class="card-body">
                        <p class="text-bold" id="message"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<div class="container-fluid mt-5" id="breadcrumb">
    <div class="row align-items-center">
        <div class="col">
            <p class="mt-2 text-success" style="font-size: 15px;">Search by Work Order Id/Company Name/customer
                zip-code/site zip-code/site location :</p>
        </div>
        <div class="col">
            <x-search-form dateSearch="yes" />
        </div>
    </div>
</div>
@endpush
@push('custom_script')
@include('user.script.workorder')
<script>
    function checkRoute() {
        const currentRoute = window.location.pathname;
        const userHomeRoute = new URL(`${window.location.origin}/user/dashboard`).pathname;
        if (currentRoute === userHomeRoute) {
            $('#defualtWorkOrder').removeClass('d-none');
            $('#allRecord').addClass('d-none');
            $('#breadcrumb').addClass('d-none');
            if (createWorkOrder(route)) {
                $('#defualtWorkOrder').addClass('d-none');
                $('#workOrderCreateForm').removeClass('d-none');
            }
        }
    }
    checkRoute();
    $(window).on('popstate', checkRoute);
</script>
<script>
    $(document).ready(function() {
        $('.delete-button').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var deleteUrl = "{{ url('user/order/delete') }}/" + id;
            $('#confirmDeleteButton').attr('href', deleteUrl);
            $('#deleteConfirmationModal').modal('show');
        });
    });
</script>
<script>
    function checkForElements() {
        var elements = $('.jodit-status-bar-link');
        if (elements.length > 0) {
            elements.attr('href', '');
            elements.attr('target', '');
            elements.text('Powered By Techbook.');
            elements.css('text-decoration', 'none');
            elements.on('click', function(e) {
                e.preventDefault();
                console.log('Link click prevented');
            });
            console.log('Elements found and updated');
        } else {
            setTimeout(checkForElements, 500);
        }
    }
    $(document).ready(checkForElements);
</script>

<script>
    let selectedFiles = []; // Array to keep track of selected files

    function handleFiles(files) {
        // Convert FileList to Array and add to selectedFiles
        selectedFiles = [...selectedFiles, ...files];
        displayImages();
        updateFileInput();
    }

    function displayImages() {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.innerHTML = ''; // Clear current previews

        selectedFiles.forEach((file, index) => {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative';
                    imgContainer.style.width = '100px';
                    imgContainer.style.height = '100px';
                    imgContainer.style.border = '1px solid #ddd';
                    imgContainer.style.padding = '4px';
                    imgContainer.style.borderRadius = '4px';
                    imgContainer.style.overflow = 'hidden';

                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.style.width = '100%';
                    imgElement.style.height = '100%';
                    imgElement.style.objectFit = 'cover';

                    // Create remove button
                    const removeButton = document.createElement('span');
                    removeButton.innerHTML = '&times;';
                    removeButton.style.position = 'absolute';
                    removeButton.style.top = '4px';
                    removeButton.style.right = '4px';
                    removeButton.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
                    removeButton.style.color = 'white';
                    removeButton.style.borderRadius = '50%';
                    removeButton.style.width = '20px';
                    removeButton.style.height = '20px';
                    removeButton.style.display = 'flex';
                    removeButton.style.alignItems = 'center';
                    removeButton.style.justifyContent = 'center';
                    removeButton.style.cursor = 'pointer';

                    // Remove file from selectedFiles when the button is clicked
                    removeButton.onclick = function() {
                        selectedFiles.splice(index, 1); // Remove file from array
                        displayImages(); // Refresh previews
                        updateFileInput(); // Update file input
                    };

                    imgContainer.appendChild(imgElement);
                    imgContainer.appendChild(removeButton);
                    imagePreview.appendChild(imgContainer);
                };

                reader.readAsDataURL(file);
            }
        });
    }

    function updateFileInput() {
        // Create a DataTransfer object to update the input's file list
        const dataTransfer = new DataTransfer();

        // Add each file in selectedFiles to the DataTransfer object
        selectedFiles.forEach(file => dataTransfer.items.add(file));

        // Update the input's files property with DataTransfer's files
        document.getElementById('pictures').files = dataTransfer.files;
    }
</script>
@endpush