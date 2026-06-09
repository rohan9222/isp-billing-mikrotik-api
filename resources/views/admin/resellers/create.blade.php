<x-app-layout>
    <x-slot name="header">
        {{ __('Create Reseller') }}
    </x-slot>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-plus-fill text-primary me-2"></i>Create New Reseller</h5>
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

            <form action="{{ route('admin.resellers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Login Credentials</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. John Doe">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. john@example.com">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required placeholder="Retype password">
                            </div>
                        </div>
                    </div>

                    <!-- Reseller Configuration -->
                    <div class="col-md-6 mb-4">
                        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Reseller Configuration</h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Company Name (Optional)</label>
                                <input type="text" name="company" class="form-control" value="{{ old('company') }}" placeholder="e.g. Acme ISP Ltd">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" required placeholder="e.g. 01751XXXXXX">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Commission Percentage (%)</label>
                                <input type="number" step="0.1" name="commission_percentage" class="form-control" value="{{ old('commission_percentage', '10.0') }}" required min="0" max="100" placeholder="e.g. 15">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Assign Admin Packages -->
                    <div class="col-12 mb-4">
                        <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Assign Admin Packages</h6>
                        <p class="text-muted small">Select which master billing packages this reseller is authorized to resell to their customers.</p>
                        
                        <div class="row g-2">
                            @forelse($packages as $package)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check card p-2.5 border border-light shadow-none bg-light-subtle h-100 rounded-3">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="packages[]" value="{{ $package->id }}" id="packageCheck{{ $package->id }}" {{ is_array(old('packages')) && in_array($package->id, old('packages')) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-dark cursor-pointer d-block" for="packageCheck{{ $package->id }}">
                                            {{ $package->package }}
                                            <span class="d-block text-muted small fw-normal">Price: BDT {{ number_format($package->price, 2) }} | Router: {{ $package->router_name ?? 'Global' }}</span>
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted">No admin packages available. Create master packages first.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Reseller Permissions -->
                    <div class="col-12 mb-4">
                        <h6 class="fw-bold text-dark mb-1 border-bottom pb-2">
                            <i class="bi bi-shield-lock-fill text-warning me-2"></i>Reseller Permissions
                        </h6>
                        <p class="text-muted small mb-3">Select which features this reseller can access from their portal. Leave all unchecked to grant no special access.</p>

                        <div class="row g-3">
                            @foreach($resellerPermissions as $groupName => $permissions)
                                <div class="col-md-6 col-xl-4">
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
                                                        id="perm_create_{{ $permName }}"
                                                        {{ in_array($permName, old('permissions', $defaultPermissions)) ? 'checked' : '' }}>
                                                    <label class="form-check-label small text-dark" for="perm_create_{{ $permName }}">
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
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.resellers.index') }}" class="btn btn-secondary rounded-3">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Save Reseller</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
