@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <h1>Invoice Logs</h1>
    
    <div class="row">
        @foreach($logs as $log)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Action: {{ ucfirst($log->action) }}</h5>
                    </div>
                    <div class="card-body">
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
</div>
@endsection
