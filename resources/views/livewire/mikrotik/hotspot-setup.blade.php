<div class="zoom-in">
    <x-slot name="header">{{ __('Hotspot Setup') }}</x-slot>

    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-wifi text-primary fs-5"></i>
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
        <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Select a connected router to manage Hotspot.</div>
    @else
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><button class="nav-link {{ $activeTab==='servers'?'active':'' }}" wire:click="$set('activeTab','servers')"><i class="bi bi-server me-1"></i>Servers & Profiles</button></li>
        <li class="nav-item"><button class="nav-link {{ $activeTab==='users'?'active':'' }}" wire:click="$set('activeTab','users')"><i class="bi bi-people me-1"></i>Users & Profiles</button></li>
        <li class="nav-item"><button class="nav-link {{ $activeTab==='sessions'?'active':'' }}" wire:click="$set('activeTab','sessions')"><i class="bi bi-activity me-1"></i>Active Sessions <span class="badge bg-success ms-1">{{ count($sessions) }}</span></button></li>
    </ul>

    {{-- SERVERS & PROFILES --}}
    @if($activeTab==='servers')
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="bi bi-server me-1"></i>Hotspot Servers on <strong>{{ $selectedRouter }}</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-hs-servers">
                        <thead class="table-light"><tr><th>Name</th><th>Interface</th><th>Address Pool</th><th>Profile</th></tr></thead>
                        <tbody>
                            @forelse($servers as $s)
                            <tr wire:key="row-hs-srv-{{ $loop->index }}-{{ $s['name'] ?? $loop->index }}">
                                <td><strong>{{ $s['name'] ?? '-' }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $s['interface'] ?? '-' }}</span></td>
                                <td><code>{{ $s['address-pool'] ?? 'none' }}</code></td>
                                <td><small>{{ $s['profile'] ?? '-' }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No hotspot servers found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><i class="bi bi-file-earmark-text me-1"></i>Server Profiles</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-hs-profiles">
                        <thead class="table-light"><tr><th>Name</th><th>Hotspot Addr</th><th>DNS Name</th><th>Auth</th></tr></thead>
                        <tbody>
                            @forelse($profiles as $p)
                            <tr wire:key="row-hs-prof-{{ $loop->index }}-{{ $p['name'] ?? $loop->index }}">
                                <td><strong>{{ $p['name'] ?? '-' }}</strong></td>
                                <td><code>{{ $p['hotspot-address'] ?? '-' }}</code></td>
                                <td><small>{{ $p['dns-name'] ?? '-' }}</small></td>
                                <td><small class="text-muted">{{ $p['login-by'] ?? '-' }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No server profiles found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- USERS & USER PROFILES --}}
    @if($activeTab==='users')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header {{ $editUserId ? 'bg-warning text-dark' : 'bg-primary text-white' }}"><i class="bi bi-{{ $editUserId ? 'pencil-square' : 'person-plus' }} me-1"></i>{{ $editUserId ? 'Edit User' : 'Add User' }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="addUser">
                        <div class="mb-2">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="u_name">
                            @error('u_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" wire:model.defer="u_password">
                            @error('u_password')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Profile <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model.defer="u_profile">
                                @forelse($userProfiles as $p)<option value="{{ $p['name'] }}">{{ $p['name'] }}</option>@empty<option value="default">default</option>@endforelse
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="u_comment">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill" wire:loading.attr="disabled" wire:target="addUser">
                                <span wire:loading.remove wire:target="addUser"><i class="bi bi-{{ $editUserId ? 'save' : 'plus-lg' }} me-1"></i>{{ $editUserId ? 'Update User' : 'Add User' }}</span>
                                <span wire:loading wire:target="addUser"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                            </button>
                            @if($editUserId)
                                <button type="button" class="btn btn-secondary btn-sm" wire:click="$set('editUserId', null)">Cancel</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header {{ $editUserProfileId ? 'bg-warning text-dark' : 'bg-info text-dark' }}"><i class="bi bi-{{ $editUserProfileId ? 'pencil-square' : 'plus-circle' }} me-1"></i>{{ $editUserProfileId ? 'Edit User Profile' : 'Add User Profile' }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="addUserProfile">
                        <div class="mb-2">
                            <label class="form-label">Profile Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="up_name">
                            @error('up_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label">Shared Users</label>
                                <input type="number" class="form-control form-control-sm" wire:model.defer="up_shared_users" min="1">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Rate Limit</label>
                                <input type="text" class="form-control form-control-sm" wire:model.defer="up_rate_limit" placeholder="1M/1M">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Session Timeout</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="up_session_timeout" placeholder="1h or 1d">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="up_comment">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info btn-sm flex-fill text-dark" wire:loading.attr="disabled" wire:target="addUserProfile">
                                <span wire:loading.remove wire:target="addUserProfile"><i class="bi bi-{{ $editUserProfileId ? 'save' : 'plus-lg' }} me-1"></i>{{ $editUserProfileId ? 'Update Profile' : 'Add Profile' }}</span>
                                <span wire:loading wire:target="addUserProfile"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                            </button>
                            @if($editUserProfileId)
                                <button type="button" class="btn btn-secondary btn-sm" wire:click="$set('editUserProfileId', null)">Cancel</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-person-badge me-1"></i>User Profiles</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-hs-user-profiles">
                        <thead class="table-light"><tr><th>Name</th><th>Shared Users</th><th>Rate Limit</th><th>Comment</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($userProfiles as $p)
                            <tr wire:key="row-hs-uprof-{{ $loop->index }}-{{ $p['name'] ?? $loop->index }}">
                                <td><strong>{{ $p['name'] ?? '-' }}</strong></td>
                                <td><span class="badge bg-primary">{{ $p['shared-users'] ?? '-' }}</span></td>
                                <td><code class="text-danger">{{ $p['rate-limit'] ?? '-' }}</code></td>
                                <td><small class="text-muted">{{ $p['comment'] ?? '' }}</small></td>
                                <td>
                        @php // removed session timeout col to save space for comment @endphp
                                    <button class="btn btn-warning btn-sm" wire:click="editUserProfile({{ json_encode($p) }})"><i class="bi bi-pencil-square"></i></button>
                                    @if(($p['default'] ?? 'no') === 'no')
                                    <button class="btn btn-danger btn-sm" wire:click="removeUserProfile('{{ $p['name'] ?? '' }}')" wire:confirm="Remove profile '{{ $p['name'] ?? '' }}'?"><i class="bi bi-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No user profiles found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="bi bi-people me-1"></i>Hotspot Users</div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-hover align-middle mb-0 data-table" wire:key="tbl-hs-users">
                        <thead class="table-light"><tr><th>User</th><th>Profile</th><th>Uptime</th><th>Bytes Used</th><th>Comment</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse($users as $u)
                            <tr wire:key="row-hs-user-{{ $loop->index }}-{{ $u['name'] ?? $loop->index }}">
                                <td><strong>{{ $u['name'] ?? '-' }}</strong></td>
                                <td><span class="badge bg-secondary">{{ $u['profile'] ?? '-' }}</span></td>
                                <td><small>{{ $u['uptime'] ?? '0s' }}</small></td>
                                <td><small class="text-muted">{{ number_format(($u['bytes-in'] ?? 0) / 1048576, 2) }} MB / {{ number_format(($u['bytes-out'] ?? 0) / 1048576, 2) }} MB</small></td>
                                <td><small class="text-muted">{{ $u['comment'] ?? '' }}</small></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" wire:click="editUser({{ json_encode($u) }})"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-danger btn-sm" wire:click="removeUser('{{ $u['name'] ?? '' }}')" wire:confirm="Remove user '{{ $u['name'] ?? '' }}'?"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No hotspot users found.</td></tr>
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
            <span><i class="bi bi-activity me-1"></i>Active Sessions on <strong>{{ $selectedRouter }}</strong></span>
            <button class="btn btn-sm btn-outline-success" wire:click="refreshSessions"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" wire:key="tbl-hs-sessions">
                <thead class="table-light"><tr><th>User</th><th>Address</th><th>MAC Address</th><th>Uptime</th><th>Server</th></tr></thead>
                <tbody>
                    @forelse($sessions as $s)
                    <tr wire:key="row-hs-sess-{{ $loop->index }}-{{ $s['user'] ?? $loop->index }}">
                        <td><strong>{{ $s['user'] ?? '-' }}</strong></td>
                        <td><code>{{ $s['address'] ?? '-' }}</code></td>
                        <td><code>{{ $s['mac-address'] ?? '-' }}</code></td>
                        <td>{{ $s['uptime'] ?? '-' }}</td>
                        <td><span class="badge bg-info text-dark">{{ $s['server'] ?? '-' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No active hotspot sessions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif
</div>
