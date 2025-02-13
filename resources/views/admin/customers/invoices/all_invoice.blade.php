@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    @include('admin.includeNew.breadcrumb')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice Number</th>
                                                <th>Work Order</th>
                                                <th>Customer</th>
                                                <th>Site</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoices as $key => $invoice)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="{{ url('customer/invoice') }}/{{ $invoice->id }}">
                                                        <span
                                                            class="badge badge-light text-primary fs-6">{{ @$invoice->invoice->invoice_number }}</span>
                                                    </a>
                                                </td>
                                                <td><a href="{{ url('customer/invoice') }}/{{ $invoice->id }}"><span
                                                            class="badge badge-light text-primary fs-6">{{ $invoice->order_id }}</span></a>
                                                </td>
                                                <td><a href="{{ url('customer/invoice') }}/{{ $invoice->id }}"><span
                                                            class="badge badge-light text-primary fs-6">{{ @$invoice->customer->company_name }}</span></a>
                                                </td>
                                                <td><a href="{{ url('customer/invoice') }}/{{ $invoice->id }}"><span
                                                            class="badge badge-light text-primary fs-6">{{ @$invoice->site->state}},{{@$invoice->site->zipcode}}({{ @$invoice->site->site_id }})</span></a>
                                                </td>
                                                <td>
                                                    <span class="badge-secondary rounded">
                                                        {{ $invoice->status == 12 ? 'Approved' : ($invoice->status == 13 ? 'Invoiced' : ($invoice->status == 15 ? 'Paid' : ($invoice->status == 14 ? 'Past Due' : $invoice->status))) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ optional($invoice->invoiceDate)->updated_at 
                                                        ? optional($invoice->invoiceDate->updated_at)->setTimezone('America/Chicago')->format('m/d/Y h:i A') 
                                                        : 'N/A' 
                                                    }}
                                                </td>


                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($invoices->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($invoices) @endphp
                    </div>
                    @endif
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