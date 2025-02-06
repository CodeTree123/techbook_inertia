@if($logs->count() > 0)
    @foreach($logs as $log)
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
    @endforeach
    <div class="d-flex justify-content-center mt-3">
        {{ $logs->links('pagination::bootstrap-4') }} <!-- Bootstrap pagination links -->
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        No logs available.
    </div>
@endif

