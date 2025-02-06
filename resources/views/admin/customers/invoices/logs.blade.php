@extends('admin.layoutsNew.app')

@section('content')
<div class="content-wrapper" style="background-color: white;">
    <h1 class="m-2 rounded text-center" style="background-color: rgb(163, 209, 139);">Logs</h1>

    <!-- Button to trigger offcanvas -->
    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#invoiceOffcanvas" aria-controls="invoiceOffcanvas">
        View Invoice Details
    </button>

    <div class="row mt-3">
        @foreach($logs as $log)
        <div class="col-md-3 mb-4 mx-0">
            <div class="card" style="border-radius:15px;">
                <div class="card-header" style="background-color: rgb(163, 209, 139);">
                    <h5>Action: {{ ucfirst($log->action) }}</h5>
                </div>
                <div class="card-body" style="height: 200px; overflow-y: auto;">
                    <p><strong>User:</strong> {{ $log->user ? $log->user->name : 'System' }}</p>
                    <p><strong>Changes:</strong> {!! $log->changes !!}</p>
                    <p><strong>Timestamp:</strong> {{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
                <div class="card-footer">
                    <p><small>Logged on: {{ $log->created_at->diffForHumans() }}</small></p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>

    <!-- Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="invoiceOffcanvas" aria-labelledby="invoiceOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 id="invoiceOffcanvasLabel">Invoice Details</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <h6><strong>Invoice Overview:</strong></h6>
            <p>Here you can show more detailed information about the invoice, including items, prices, totals, etc.</p>
            <!-- You can dynamically load invoice details here -->
            <ul class="list-group">
                <li class="list-group-item">Item 1: $10.00</li>
                <li class="list-group-item">Item 2: $20.00</li>
                <li class="list-group-item">Total: $30.00</li>
            </ul>
        </div>
    </div>

</div>
@endsection
