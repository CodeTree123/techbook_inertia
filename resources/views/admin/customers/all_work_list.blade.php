@extends('admin.layoutsNew.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('assetsNew/main_css/customer/all_work_list.css
                            ') }}">
    <div class="content-wrapper" style="background-color: white;">
        <!-- Content Header (Page header) -->
        @include('admin.includeNew.breadcrumb')
        <!-- /.content-header -->
        <div class="container-fluid text-dark p-1">
            <div class="card p-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th># SL</th>
                                    <th>Order ID</th>
                                    <th>Opened</th>
                                    <th>Site Location</th>
                                    <th>Hours Of Operation</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workOrders as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ url('customer/order/view') }}/{{ $order->id }}">
                                                <span
                                                    class="badge badge-light text-primary fs-6">{{ $order->order_id }}</span>
                                            </a>
                                        </td>
                                        <td>{{ $order->open_date }}</td>
                                        <td><a href="{{ url('customer/order/view') }}/{{ $order->id }}"><span
                                                    class="badge badge-light text-primary fs-6">{{ @$order->site->location }}</span></a>
                                        </td>
                                        <td>{{ @$order->h_operation }}</td>
                                        <td>
                                            {{ implode(
                                                ', ',
                                                array_filter([
                                                    @$order->site->address_1,
                                                    @$order->site->address_2,
                                                    @$order->site->city,
                                                    @$order->site->state,
                                                    @$order->site->zipcode,
                                                ]),
                                            ) }}
                                        </td>
                                        <td>
                                            <div class=" dropdown">
                                                <i class="fas fa-ellipsis-v custom-icon mx-3" style="cursor: pointer;"
                                                    dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"></i>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        href="{{ url('/customer/order/edit') }}/{{ $order->id }}"><i
                                                            class="fas fa-pencil-alt"></i> Edit</a>
                                                    <a class="dropdown-item text-danger delete-button"
                                                        href="{{ url('/customer/order/delete') }}/{{ $order->id }}"
                                                        data-toggle="modal"
                                                        data-target="#confirmationModal-{{ $order->id }}"><i
                                                            class="fas fa-trash-alt"></i> Delete</a>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($workOrders->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($workOrders) @endphp
                    </div>
                @endif
            </div>
        </div>
        <div class="modal fade" id="confirmationModal-{{ @$order->id }}" tabindex="-1" role="dialog"
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
                        Are you sure you want to delete this WorkOrder?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="{{ url('/customer/order/delete') }}/{{ @$order->id }}"
                            class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <p class="font-weight-light p-2 m-2">Do Search by Work Order Number :</p>
    <x-search-form dateSearch='yes' />
@endpush
