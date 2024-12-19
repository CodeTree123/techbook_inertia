@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">Associate Work Order</div>
                    <div class="card-body" style="display: flex; justify-content: center;">
                        <table class="table table-responsive text-center" style="width: auto;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Work Order Id</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrder as $em)
                                <tr>
                                    <th scope="row">{{$em->id}}</th>
                                    <td>{{$em->order_id}}</td>
                                    <td><a class="btn btn-primary" href="{{ route('employee.workOrder.view', $em->id) }}">View</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($workOrder->hasPages())
                    <div class="card-footer py-4">
                        <p class="text-italic">Click below to see next page</p> @php echo paginateLinks($workOrder) @endphp
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
    <p class="font-weight-light p-2 m-2">Search by Work order Id :</p>
    <x-search-form dateSearch='no' />
@endpush