@extends('admin.layoutsNew.app')
@section('content')
<div class="content-wrapper" style="background-color: white;">
    <h1 class="m-2 rounded text-center" style="background-color: rgb(163, 209, 139);">Logs</h1>
    <div class="row">
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
</div>
@endsection