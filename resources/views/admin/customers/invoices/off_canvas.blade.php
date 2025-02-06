<!-- Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="invoiceOffcanvas" aria-labelledby="invoiceOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 id="invoiceOffcanvasLabel">Log Details</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
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
    </div>
</div>