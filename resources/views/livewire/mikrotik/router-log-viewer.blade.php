<div>
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card shadow-none border">
                    <div class="card-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                                </svg>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Router Log Viewer</h5>
                                <small class="text-muted">
                                    Live logs from MikroTik &mdash;
                                    @if($logServerEnabled)
                                        <span class="badge bg-success-subtle text-success">Log Server: ON</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Log Server: OFF</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="autoRefreshToggle" wire:model.live="autoRefresh">
                                <label class="form-check-label small" for="autoRefreshToggle">Auto-refresh</label>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" wire:click="pollLogs" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="pollLogs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                    Refresh
                                </span>
                                <span wire:loading wire:target="pollLogs">
                                    <span class="spinner-border spinner-border-sm"></span> Fetching…
                                </span>
                            </button>
                            @if($logServerEnabled)
                            <button class="btn btn-sm btn-outline-danger" wire:click="clearOldLogs" wire:confirm="Delete old logs based on retention policy?">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                Prune Logs
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Bar --}}
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <select class="form-select form-select-sm" wire:model.live="selectedRouter">
                    <option value="">— All Routers —</option>
                    @foreach($routers as $router)
                        <option value="{{ $router }}">{{ $router }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" wire:model.live="filterTopic">
                    <option value="">— All Topics —</option>
                    <option value="info">Info</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                    <option value="critical">Critical</option>
                    <option value="firewall">Firewall</option>
                    <option value="ppp">PPP</option>
                    <option value="account">Account</option>
                    <option value="dhcp">DHCP</option>
                    <option value="system">System</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" wire:model.live="filterBuffer">
                    <option value="">— All Buffers —</option>
                    <option value="memory">Memory</option>
                    <option value="disk">Disk</option>
                    <option value="remote">Remote</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </span>
                    <input type="text" class="form-control" placeholder="Search message…" wire:model.live.debounce.500ms="searchMessage">
                </div>
            </div>
        </div>

        {{-- Log Table --}}
        @if($logServerEnabled)
        <div class="card shadow-none border">
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 70vh; overflow-y:auto;">
                    <table class="table table-sm table-hover mb-0 fs--1 align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width:160px">Time</th>
                                <th style="width:100px">Router</th>
                                <th style="width:120px">Topics</th>
                                <th style="width:80px">Buffer</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $color = match(true) {
                                        str_contains($log->topics, 'error') || str_contains($log->topics, 'critical') => 'danger',
                                        str_contains($log->topics, 'warning') => 'warning',
                                        str_contains($log->topics, 'firewall') => 'warning',
                                        str_contains($log->topics, 'ppp') || str_contains($log->topics, 'account') => 'success',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr>
                                    <td class="text-muted text-nowrap">
                                        <span class="font-monospace fs--2">{{ $log->time ?? $log->created_at->format('H:i:s') }}</span><br>
                                        <span class="fs--2 text-muted">{{ $log->created_at->format('Y-m-d') }}</span>
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $log->router_name }}</span></td>
                                    <td><span class="badge bg-{{ $color }}-subtle text-{{ $color }} text-truncate" style="max-width:110px" title="{{ $log->topics }}">{{ $log->topics }}</span></td>
                                    <td><span class="badge bg-light text-muted border">{{ $log->buffer ?? 'memory' }}</span></td>
                                    <td class="font-monospace text-break">{{ $log->message }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mb-2 opacity-50"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        <p class="mb-1">No stored logs yet.</p>
                                        <small>Click <strong>Refresh</strong> to fetch and store logs from the router.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
            <div class="card-footer py-2">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
        @else
        {{-- Log server is disabled: show live log stream only --}}
        <div class="card shadow-none border">
            <div class="card-body">
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    <div>
                        <strong>Log Server is disabled.</strong> Logs are shown live only — not stored to the database.
                        Go to <a href="{{ route('site-settings') }}" wire:navigate>Site Settings</a> to enable log storage.
                    </div>
                </div>

                {{-- Live stream terminal --}}
                <div id="log-terminal" class="bg-dark text-success rounded p-3 font-monospace overflow-auto" style="height:65vh; font-size:0.78rem;"
                    wire:ignore
                    x-data="{ lines: [] }"
                    x-init="
                        window.addEventListener('logs-refreshed', (e) => {
                            let entries = e.detail[0]?.logs || e.detail?.logs || [];
                            if (!Array.isArray(entries)) {
                                if (Array.isArray(e.detail)) entries = e.detail;
                                else if (Array.isArray(e.detail[0])) entries = e.detail[0];
                                else entries = Object.values(entries || {});
                            }
                            
                            (Array.isArray(entries) ? entries : []).forEach(line => {
                                let color = 'text-success';
                                const t = (line.topics || '').toLowerCase();
                                if (t.includes('error') || t.includes('critical')) color = 'text-danger';
                                else if (t.includes('warning') || t.includes('firewall')) color = 'text-warning';
                                else if (t.includes('info')) color = 'text-info';
                                lines.push({ ...line, color });
                            });
                            if (lines.length > 800) lines = lines.slice(-800);
                            $nextTick(() => { $el.scrollTop = $el.scrollHeight; });
                        });
                    ">
                    <template x-for="(line, i) in lines" :key="i">
                        <div :class="line.color" class="mb-0 lh-sm">
                            <span class="text-muted me-2" x-text="line.time || ''"></span>
                            <span class="badge bg-secondary me-1" x-text="line.buffer || 'memory'"></span>
                            <span class="fw-bold me-2" x-text="'[' + (line.topics || '') + ']'"></span>
                            <span x-text="line.message || ''"></span>
                        </div>
                    </template>
                    <div x-show="lines.length === 0" class="text-secondary text-center pt-5">
                        <p>Waiting for logs… click <strong>Refresh</strong> or select a router.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Auto-poll --}}
        @if($autoRefresh && $selectedRouter)
        <div wire:poll.10000ms="pollLogs" class="d-none"></div>
        @endif
    </div>
</div>
