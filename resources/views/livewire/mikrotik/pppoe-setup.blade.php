<div class="zoom-in">
    <x-slot name="header">{{ __('PPPoE Setup') }}</x-slot>

    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-router-fill text-primary fs-5"></i>
        <select class="form-select form-select-sm w-auto" wire:model.live="selectedRouter">
            <option value="">-- Select Router --</option>
            @foreach($routers as $r)<option value="{{ $r->router_name }}">{{ $r->router_name }} ({{ $r->ip_address }})</option>@endforeach
        </select>
        @if($selectedRouter)
            <button class="btn btn-sm btn-outline-secondary" wire:click="loadData">
                <span wire:loading.remove wire:target="loadData"><i class="bi bi-arrow-clockwise"></i> Refresh</span>
                <span wire:loading wire:target="loadData"><span class="spinner-border spinner-border-sm"></span></span>
            </button>
        @endif
    </div>

    @if(!$selectedRouter)
        <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Select a connected router.</div>
    @else
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><button class="nav-link {{ $activeTab==='servers'?'active':'' }}" wire:click="$set('activeTab','servers')"><i class="bi bi-server me-1"></i>PPPoE Servers</button></li>
        <li class="nav-item"><button class="nav-link {{ $activeTab==='profiles'?'active':'' }}" wire:click="$set('activeTab','profiles')"><i class="bi bi-person-lines-fill me-1"></i>PPP Profiles</button></li>
        <li class="nav-item"><button class="nav-link {{ $activeTab==='secrets'?'active':'' }}" wire:click="$set('activeTab','secrets')"><i class="bi bi-key me-1"></i>PPP Secrets</button></li>
        <li class="nav-item"><button class="nav-link {{ $activeTab==='sessions'?'active':'' }}" wire:click="$set('activeTab','sessions')"><i class="bi bi-activity me-1"></i>Active Sessions <span class="badge bg-success ms-1">{{ count($activeSessions) }}</span></button></li>
    </ul>

    {{-- SERVERS --}}
    @if($activeTab==='servers')
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header {{ $editServerId ? 'bg-warning text-dark' : 'bg-primary text-white' }}"><i class="bi bi-{{ $editServerId ? 'pencil-square' : 'plus-circle' }} me-1"></i>{{ $editServerId ? 'Edit PPPoE Server' : 'Add PPPoE Server' }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="addPppoeServer">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="srv_name" placeholder="pppoe-in1">
                                @error('srv_name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Interface <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" wire:model.defer="srv_interface">
                                    <option value="">-- Select --</option>
                                    @foreach($interfaces as $i)<option value="{{ $i }}">{{ $i }}</option>@endforeach
                                </select>
                                @error('srv_interface')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Service Name</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="srv_service_name" placeholder="pppoe-server">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Authentication</label>
                                <select class="form-select form-select-sm" wire:model.defer="srv_authentication">
                                    <option value="mschap2">mschap2</option>
                                    <option value="chap">chap</option>
                                    <option value="pap">pap</option>
                                </select>
                            </div>
                            <div class="col-4"><label class="form-label">Max MTU</label><input type="number" class="form-control form-control-sm" wire:model.defer="srv_max_mtu"></div>
                            <div class="col-4"><label class="form-label">Max MRU</label><input type="number" class="form-control form-control-sm" wire:model.defer="srv_max_mru"></div>
                            <div class="col-4"><label class="form-label">Keepalive (s)</label><input type="number" class="form-control form-control-sm" wire:model.defer="srv_keepalive"></div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-fill" wire:loading.attr="disabled" wire:target="addPppoeServer">
                                    <span wire:loading.remove wire:target="addPppoeServer"><i class="bi bi-{{ $editServerId ? 'save' : 'plus-lg' }} me-1"></i>{{ $editServerId ? 'Update Server' : 'Add Server' }}</span>
                                    <span wire:loading wire:target="addPppoeServer"><span class="spinner-border spinner-border-sm me-1"></span>Adding...</span>
                                </button>
                                @if($editServerId)
                                    <button type="button" class="btn btn-secondary btn-sm" wire:click="$set('editServerId', null)">Cancel</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><i class="bi bi-server me-1"></i>PPPoE Servers on <strong>{{ $selectedRouter }}</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" wire:key="container-pppoe-servers-{{ $selectedRouter }}">
                    <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-pppoe-servers">
                        <thead class="table-light"><tr><th>Name</th><th>Interface</th><th>Service</th><th>Auth</th><th>MTU</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($pppoeServers as $srv)
                            <tr wire:key="row-ppp-srv-{{ $loop->index }}-{{ $srv['name'] ?? $loop->index }}">
                                <td><strong>{{ $srv['name'] ?? '-' }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $srv['interface'] ?? '-' }}</span></td>
                                <td>{{ $srv['service-name'] ?? '-' }}</td>
                                <td><small>{{ $srv['authentication'] ?? '-' }}</small></td>
                                <td>{{ $srv['max-mtu'] ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="editPppoeServer({{ json_encode($srv) }})"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-danger btn-sm" wire:click="removePppoeServer('{{ $srv['name'] ?? '' }}')" wire:confirm="Remove this server?"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No PPPoE servers found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- PROFILES --}}
    @if($activeTab==='profiles')
    <div class="card">
        <div class="card-header"><i class="bi bi-person-lines-fill me-1"></i>PPP Profiles on <strong>{{ $selectedRouter }}</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive" wire:key="container-ppp-profiles-{{ $selectedRouter }}">
            <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-ppp-profiles">
                <thead class="table-light"><tr><th>Name</th><th>Rate Limit</th><th>Local Address</th><th>Remote Address</th><th>Session Timeout</th></tr></thead>
                <tbody>
                    @forelse($pppProfiles as $p)
                    <tr wire:key="row-ppp-prof-{{ $loop->index }}-{{ $p['name'] ?? $loop->index }}">
                        <td><strong>{{ $p['name'] ?? '-' }}</strong></td>
                        <td><code class="text-danger">{{ $p['rate-limit'] ?? '-' }}</code></td>
                        <td><code>{{ $p['local-address'] ?? '-' }}</code></td>
                        <td><code>{{ $p['remote-address'] ?? '-' }}</code></td>
                        <td>{{ $p['session-timeout'] ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No profiles found. Manage profiles via <a href="{{ route('package-list-setup') }}">Package Setup</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- SECRETS --}}
    @if($activeTab==='secrets')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header {{ $editSecretId ? 'bg-warning text-dark' : 'bg-primary text-white' }}"><i class="bi bi-{{ $editSecretId ? 'pencil-square' : 'plus-circle' }} me-1"></i>{{ $editSecretId ? 'Edit PPP Secret' : 'Add PPP Secret' }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="addSecret">
                        <div class="mb-2">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="sec_name">
                            @error('sec_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" wire:model.defer="sec_password">
                            @error('sec_password')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Profile <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model.defer="sec_profile">
                                <option value="default">default</option>
                                @foreach($pppProfiles as $p)<option value="{{ $p['name'] }}">{{ $p['name'] }}</option>@endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Service</label>
                            <select class="form-select form-select-sm" wire:model.defer="sec_service">
                                <option value="pppoe">pppoe</option><option value="l2tp">l2tp</option><option value="pptp">pptp</option><option value="any">any</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="sec_comment">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="bi bi-{{ $editSecretId ? 'save' : 'plus-lg' }} me-1"></i>{{ $editSecretId ? 'Update Secret' : 'Add Secret' }}</button>
                            @if($editSecretId)
                                <button type="button" class="btn btn-secondary btn-sm" wire:click="$set('editSecretId', null)">Cancel</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-key me-1"></i>PPP Secrets on <strong>{{ $selectedRouter }}</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" wire:key="container-ppp-secrets-{{ $selectedRouter }}">
                    <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-ppp-secrets">
                        <thead class="table-light"><tr><th>Name</th><th>Profile</th><th>Service</th><th>IP</th><th>Comment</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($pppSecrets as $s)
                            <tr wire:key="row-ppp-sec-{{ $loop->index }}-{{ $s['name'] ?? $loop->index }}">
                                <td><strong>{{ $s['name'] ?? '-' }}</strong></td>
                                <td><code>{{ $s['profile'] ?? '-' }}</code></td>
                                <td><span class="badge bg-info text-dark">{{ $s['service'] ?? '-' }}</span></td>
                                <td><small>{{ $s['caller-id'] ?? '' }}</small></td>
                                <td><small class="text-muted">{{ $s['comment'] ?? '' }}</small></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="editSecret({{ json_encode($s) }})"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-danger btn-sm" wire:click="removeSecret('{{ $s['name'] ?? '' }}')" wire:confirm="Delete secret '{{ $s['name'] ?? '' }}'?"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No PPP secrets found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- SESSIONS --}}
    @if($activeTab==='sessions')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-activity me-1"></i>Active PPP Sessions on <strong>{{ $selectedRouter }}</strong></span>
            <button class="btn btn-sm btn-outline-success" wire:click="refreshSessions"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" wire:key="container-ppp-sessions-{{ $selectedRouter }}">
            <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-ppp-active-sessions">
                <thead class="table-light"><tr><th>Name</th><th>Service</th><th>Address</th><th>Uptime</th><th>Caller ID</th></tr></thead>
                <tbody>
                    @forelse($activeSessions as $s)
                    <tr wire:key="row-ppp-session-{{ $loop->index }}-{{ $s['name'] ?? $loop->index }}">
                        <td><strong>{{ $s['name'] ?? '-' }}</strong></td>
                        <td><span class="badge bg-success">{{ $s['service'] ?? '-' }}</span></td>
                        <td><code>{{ $s['address'] ?? '-' }}</code></td>
                        <td>{{ $s['uptime'] ?? '-' }}</td>
                        <td><small>{{ $s['caller-id'] ?? '-' }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No active sessions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif
</div>
