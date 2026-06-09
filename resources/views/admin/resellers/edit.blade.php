<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Reseller') }}
    </x-slot>

    <div class="row">
        <!-- Edit Form Column -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Reseller Profile</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.resellers.update', $reseller->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $reseller->user->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $reseller->user->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $reseller->phone) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Company Name (Optional)</label>
                                <input type="text" name="company" class="form-control" value="{{ old('company', $reseller->company) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Commission Percentage (%)</label>
                                <input type="number" step="0.1" name="commission_percentage" class="form-control" value="{{ old('commission_percentage', $reseller->commission_percentage) }}" required min="0" max="100">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status', $reseller->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ old('status', $reseller->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Update Password (Leave blank to keep current)</label>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input type="password" name="password" class="form-control" placeholder="New password">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Assign Admin Packages</label>
                            <div class="row g-2">
                                @forelse($packages as $package)
                                    <div class="col-md-6">
                                        <div class="form-check card p-2 border border-light-subtle bg-light-subtle shadow-none rounded-3">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="packages[]" value="{{ $package->id }}" id="packageCheck{{ $package->id }}" {{ in_array($package->id, $assignedPackageIds) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark cursor-pointer" for="packageCheck{{ $package->id }}">
                                                {{ $package->package }}
                                                <span class="d-block text-muted small fw-normal">Price: BDT {{ number_format($package->price, 2) }} | Router: {{ $package->router_name ?? 'Global' }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted text-center small">No packages available to assign.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Reseller Permissions -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-1">
                                <i class="bi bi-shield-lock-fill text-warning me-2"></i>Reseller Permissions
                            </label>
                            <p class="text-muted small mb-3">Grant or revoke which features this reseller can access from their portal.</p>

                            <div class="row g-3">
                                @foreach($resellerPermissions as $groupName => $permissions)
                                    <div class="col-md-6">
                                        <div class="card border border-light-subtle rounded-3 h-100" style="background: #f8faff;">
                                            <div class="card-header border-0 pb-1 pt-3 px-3" style="background: transparent;">
                                                <span class="fw-bold text-dark" style="font-size: 0.82rem;">
                                                    @if($groupName === 'Customer Management')
                                                        <i class="bi bi-people text-primary me-1"></i>
                                                    @elseif($groupName === 'Customer Status Control')
                                                        <i class="bi bi-toggle2-on text-success me-1"></i>
                                                    @elseif($groupName === 'Billing & Payments')
                                                        <i class="bi bi-cash-coin text-info me-1"></i>
                                                    @elseif($groupName === 'Reports & Lists')
                                                        <i class="bi bi-bar-chart-line text-warning me-1"></i>
                                                    @elseif($groupName === 'Setup & Access')
                                                        <i class="bi bi-gear-fill text-secondary me-1"></i>
                                                    @else
                                                        <i class="bi bi-box2 text-secondary me-1"></i>
                                                    @endif
                                                    {{ $groupName }}
                                                </span>
                                            </div>
                                            <div class="card-body pt-2 px-3">
                                                @foreach($permissions as $permName => $permLabel)
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permName }}"
                                                            id="perm_edit_{{ $permName }}"
                                                            {{ in_array($permName, old('permissions', $userPermissions)) ? 'checked' : '' }}>
                                                        <label class="form-check-label small text-dark" for="perm_edit_{{ $permName }}">
                                                            {{ $permLabel }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-3" onclick="document.querySelectorAll('[name=\'permissions[]\']').forEach(cb => cb.checked = true)">
                                    <i class="bi bi-check-all me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" onclick="document.querySelectorAll('[name=\'permissions[]\']').forEach(cb => cb.checked = false)">
                                    <i class="bi bi-x-circle me-1"></i>Clear All
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('admin.resellers.index') }}" class="btn btn-secondary rounded-3">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">Update Reseller</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Wallet Info Column -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white;">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold mb-2 text-white-50" style="font-size: 0.75rem; letter-spacing: 1px;">Wallet Status</h6>
                    <h2 class="fw-bold mb-1">৳{{ number_format($reseller->balance, 2) }}</h2>
                    <p class="small text-white-50 mb-0">Reseller ID: #{{ $reseller->id }}</p>
                    
                    <div class="mt-4 pt-3 border-top border-white-50 border-opacity-25 d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success rounded-3 w-100" data-bs-toggle="modal" data-bs-target="#adjustModal">
                            <i class="bi bi-cash-coin me-1"></i>Adjust Balance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Adjust Balance Modal -->
            <div class="modal fade text-start" id="adjustModal" tabindex="-1" aria-labelledby="adjustModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.resellers.adjust-balance', $reseller->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="adjustModalLabel">Adjust Wallet: {{ $reseller->user->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Adjustment Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="credit">Credit (Add Balance)</option>
                                        <option value="debit">Debit (Deduct Balance)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Amount (BDT)</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required min="0.01">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description / Reason</label>
                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Apply Adjustment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Activity Logs -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history text-warning me-2"></i>Recent Activity Logs</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        @forelse($logs as $log)
                            <div class="list-group-item py-2.5">
                                <div class="small fw-semibold text-dark">{{ $log->description }}</div>
                                <div class="d-flex justify-content-between mt-1 text-muted" style="font-size: 0.72rem;">
                                    <span>By: {{ $log->causer->name ?? 'System' }}</span>
                                    <span>{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-3 text-center text-muted small">No activity logs recorded.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
