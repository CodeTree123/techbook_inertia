@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white; padding: 20px;">

    <div class="d-flex justify-content-between mb-3">
        <h1 class="rounded text-center" style="background-color: rgb(102, 163, 255); padding: 10px; color: white;">Logs</h1>
        <a class="btn align-self-center" href="{{ route('customer.invoice', $invoice) }}" style="background-color: rgb(146,201,117);">Home</a>
    </div>

    @if($logs->count() > 0)
        <div class="row">
            @foreach($logs as $log)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-header text-white" style="background-color: rgb(102, 163, 255);">
                        <h5 class="mb-0">Action: {{ ucfirst($log->action) }}</h5>
                    </div>
                    <div class="card-body" style="height: 200px; overflow-y: auto; padding: 15px;">
                        <p><strong>User:</strong> {{ $log->user ? $log->user->name : 'System' }}</p>
                        <p><strong>Changes:</strong> {!! $log->changes !!}</p>
                        <p><strong>Timestamp:</strong> {{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="card-footer text-muted">
                        <small>Logged on: {{ $log->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">
            No logs available.
        </div>
    @endif

</div>
@endsection
