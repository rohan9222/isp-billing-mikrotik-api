<div>
    @php
        $logCount = \App\Models\MikrotikLog::count();
        $retention = \App\Models\MainSiteData::getValue('log_retention_days', 30);
        $enabled = \App\Models\MainSiteData::getValue('log_server_enabled', false);
    @endphp

    <div class="card bg-light-info border-0 shadow-sm mt-3">
        <div class="card-body p-4 text-center">
            <div class="d-flex align-items-center justify-content-center gap-4">
                <div class="text-start">
                    <h2 class="mb-0 fw-extrabold text-info">{{ number_format($logCount) }}</h2>
                    <p class="text-muted small mb-0 font-monospace">Persisted Log Entries in Database</p>
                </div>
                <div class="vr mx-2"></div>
                <div class="text-start">
                    <h4 class="mb-0 fw-bold">{{ $retention }} Days</h4>
                    <p class="text-muted small mb-0">Automatic Retention Threshold</p>
                </div>
                <div class="ms-auto">
                    @if($enabled)
                        <span class="badge bg-success shadow-sm px-3 py-2"><i class="bi bi-cpu me-1"></i> LOGGER ACTIVE</span>
                    @else
                        <span class="badge bg-secondary shadow-sm px-3 py-2"><i class="bi bi-pause-circle me-1"></i> PAUSED</span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2 justify-content-center">
                <a href="{{ route('mikrotik-log-viewer') }}" class="btn btn-sm btn-info px-4 fw-bold shadow-sm rounded-pill">
                    <i class="bi bi-table me-1"></i> OPEN FULL DATA ARCHIVE
                </a>
                <button type="button" wire:click="pollLogs" class="btn btn-sm btn-outline-info px-4 fw-bold shadow-sm rounded-pill">
                    <i class="bi bi-arrow-clockwise me-1"></i> POLL NOW
                </button>
            </div>
        </div>
    </div>
</div>
