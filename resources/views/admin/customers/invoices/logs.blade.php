
@if($logs->count() > 0)
<div class="accordion accordion-flush" id="logsAccordion">

@foreach($logs as $log)
    @php
        $logId = 'logCollapse' . $log->id;
        $updatedId = 'logUpdated' . $log->id;
        $previousId = 'logPrevious' . $log->id;
        $headingId = 'logHeading' . $log->id;
        $headingIdUpdated = 'logHeadingUpdated' . $log->id;
        $headingIdPrevious = 'logHeadingPrevious' . $log->id;
    @endphp

    <div class="accordion-item">
        <h2 class="accordion-header" id="{{ $headingId }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $logId }}" aria-expanded="false" aria-controls="{{ $logId }}">
                {{ ucfirst($log->action) }}
            </button>
        </h2>
        <div id="{{ $logId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingId }}">
            <div class="accordion-body">
                <p><strong>User:</strong> {{ $log->user ? $log->user->name : 'System' }}</p>

                @php
                    $parts = explode('|', $log->changes);
                    $updatedData = trim($parts[0]); // Current data
                    $previousData = isset($parts[1]) ? trim($parts[1]) : null;
                @endphp

                @if($previousData)
                <!-- Unique Previous Data Accordion -->
                <div class="accordion accordion-flush" id="previousData{{ $log->id }}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="{{ $headingIdPrevious }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $previousId }}" aria-expanded="false" aria-controls="{{ $previousId }}">
                                Previous
                            </button>
                        </h2>
                        <div id="{{ $previousId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingIdPrevious }}">
                            <div class="accordion-body">
                                <p>{!! $previousData !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Unique Updated Data Accordion -->
                <div class="accordion accordion-flush" id="updatedData{{ $log->id }}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="{{ $headingIdUpdated }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $updatedId }}" aria-expanded="false" aria-controls="{{ $updatedId }}">
                                Updated
                            </button>
                        </h2>
                        <div id="{{ $updatedId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingIdUpdated }}">
                            <div class="accordion-body">
                                <p>{!! $updatedData !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <p><strong>Updated at:</strong> {{ \Carbon\Carbon::parse($log->created_at)->setTimezone('America/Chicago')->format('m/d/Y h:i:s A') }}
                    <br>({{ $log->created_at->diffForHumans() }})
                </p>
            </div>
        </div>
    </div>
@endforeach

</div>

<div class="d-flex justify-content-center mt-3">
    {{ $logs->links('pagination::bootstrap-4') }} <!-- Bootstrap pagination links -->
</div>
@else
<div class="alert alert-info text-center" role="alert">
    No logs available.
</div>
@endif