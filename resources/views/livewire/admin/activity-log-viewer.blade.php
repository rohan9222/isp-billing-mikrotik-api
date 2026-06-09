<div>
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-0" style="color:#1a1f36;">
                    <i class="bi bi-clock-history me-2 text-primary"></i>System Activity Logs
                </h4>
                <p class="text-muted small mb-0">Monitor and audit system transactions, updates, and deletions</p>
            </div>
            <button wire:click="resetFilters" class="btn btn-outline-secondary rounded-3 px-3 fw-semibold shadow-sm">
                <i class="bi bi-arrow-repeat me-1"></i> Reset Filters
            </button>
        </div>

        {{-- Filter Bar --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body py-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-muted mb-1">Search Keywords</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live.debounce.400ms="search" type="text" 
                                placeholder="Search description, values, causer..." 
                                class="form-control form-control-sm rounded-end-3 border-start-0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Log Category</label>
                        <select wire:model.live="logName" class="form-select form-select-sm rounded-3">
                            <option value="all">All Categories</option>
                            @foreach($logNames as $name)
                                <option value="{{ $name }}">{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-1">Event Type</label>
                        <select wire:model.live="event" class="form-select form-select-sm rounded-3">
                            <option value="all">All Events</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev }}">{{ strtoupper($ev) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Log Table --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fc;">
                            <tr class="small text-muted fw-semibold">
                                <th class="ps-4 py-3">Timestamp</th>
                                <th>Log Name</th>
                                <th>Event</th>
                                <th>Description</th>
                                <th>User (Causer)</th>
                                <th>Subject</th>
                                <th class="text-end pe-4">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr wire:key="log-{{ $log->id }}">
                                <td class="ps-4 small text-muted">
                                    {{ $log->created_at->format('d M Y, h:i A') }}
                                    <div class="x-small text-muted" style="font-size:0.75rem;">({{ $log->created_at->diffForHumans() }})</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill small px-2">
                                        {{ ucfirst($log->log_name) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($log->event) {
                                            'created' => 'success',
                                            'updated' => 'warning',
                                            'deleted' => 'danger',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} border border-{{ $badgeColor }}-subtle rounded-pill small px-2">
                                        {{ strtoupper($log->event ?: 'system') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold small text-dark" style="max-width: 350px; white-space: normal;">
                                        {{ $log->description }}
                                    </div>
                                </td>
                                <td class="small">
                                    @if($log->causer)
                                        <div class="fw-semibold">{{ $log->causer->name }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $log->causer->email }}</div>
                                    @else
                                        <span class="text-muted small">System / API</span>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    @if($log->subject_type)
                                        <div>{{ class_basename($log->subject_type) }}</div>
                                        <div class="x-small">ID: {{ $log->subject_id }}</div>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <button wire:click="showDetails({{ $log->id }})"
                                        class="btn btn-sm btn-outline-primary rounded-3 py-1 px-2.5">
                                        <i class="bi bi-info-circle me-1"></i> View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No activity logs found matching the filters.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Details Modal --}}
    @if($selectedLog)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>Log Entry Details
                    </h5>
                    <button wire:click="closeDetails" class="btn-close" type="button"></button>
                </div>
                <div class="modal-body px-4 pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="d-block small text-muted fw-bold">Time</span>
                            <span class="fw-semibold text-dark">{{ $selectedLog->created_at->format('d M Y, h:i:s A') }} ({{ $selectedLog->created_at->diffForHumans() }})</span>
                        </div>
                        <div class="col-md-3">
                            <span class="d-block small text-muted fw-bold">Event</span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1">
                                {{ strtoupper($selectedLog->event ?: 'SYSTEM') }}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <span class="d-block small text-muted fw-bold">Log Category</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                                {{ ucfirst($selectedLog->log_name) }}
                            </span>
                        </div>
                        <div class="col-12">
                            <span class="d-block small text-muted fw-bold">Description</span>
                            <span class="fw-semibold text-dark fs-6">{{ $selectedLog->description }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="d-block small text-muted fw-bold">User / Causer</span>
                            @if($selectedLog->causer)
                                <span class="fw-bold text-dark">{{ $selectedLog->causer->name }}</span>
                                <span class="d-block text-muted small">{{ $selectedLog->causer->email }}</span>
                            @else
                                <span class="text-muted">System / Background Worker</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <span class="d-block small text-muted fw-bold">Subject Record</span>
                            @if($selectedLog->subject_type)
                                <span class="fw-bold text-dark">{{ $selectedLog->subject_type }}</span>
                                <span class="d-block text-muted small">Record ID: {{ $selectedLog->subject_id }}</span>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </div>
                        
                        <div class="col-12">
                            <hr class="my-3 text-muted opacity-25">
                            <h6 class="fw-bold mb-2">Properties / Metadata</h6>
                            <div class="bg-light p-3 rounded-3 font-mono" style="max-height: 250px; overflow-y: auto;">
                                @if(!empty($selectedLog->properties) && count($selectedLog->properties) > 0)
                                    @if(isset($selectedLog->properties['attributes']) || isset($selectedLog->properties['old']))
                                        {{-- Updated/Created state changes --}}
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered bg-white text-start mb-0 align-middle">
                                                <thead>
                                                    <tr class="table-secondary small">
                                                        <th>Field</th>
                                                        @if(isset($selectedLog->properties['old']))
                                                            <th>Old Value</th>
                                                        @endif
                                                        <th>New/Current Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    @php
                                                        $attributes = $selectedLog->properties['attributes'] ?? [];
                                                        $oldValues = $selectedLog->properties['old'] ?? [];
                                                        $allKeys = array_keys(array_merge($attributes, $oldValues));
                                                    @endphp
                                                    @foreach($allKeys as $key)
                                                        @if($key === 'password' || $key === 'token')
                                                            @continue
                                                        @endif
                                                        <tr>
                                                            <td class="fw-bold text-muted">{{ $key }}</td>
                                                            @if(isset($selectedLog->properties['old']))
                                                                <td class="text-danger">{{ is_array($oldValues[$key] ?? '') ? json_encode($oldValues[$key]) : ($oldValues[$key] ?? '—') }}</td>
                                                            @endif
                                                            <td class="text-success">{{ is_array($attributes[$key] ?? '') ? json_encode($attributes[$key]) : ($attributes[$key] ?? '—') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        {{-- Generic custom values (like transaction deletion properties) --}}
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered bg-white text-start mb-0 align-middle">
                                                <thead>
                                                    <tr class="table-secondary small">
                                                        <th>Property</th>
                                                        <th>Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="small">
                                                    @foreach($selectedLog->properties as $key => $val)
                                                        <tr>
                                                            <td class="fw-bold text-muted">{{ $key }}</td>
                                                            <td class="text-dark fw-semibold">
                                                                @if(is_array($val))
                                                                    <pre class="mb-0 text-start" style="font-size:0.75rem;">{{ json_encode($val, JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $val }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted small">No property changes recorded.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button wire:click="closeDetails" class="btn btn-secondary rounded-3 px-4">Close Details</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
