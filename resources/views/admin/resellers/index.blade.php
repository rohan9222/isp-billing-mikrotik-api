<x-app-layout>
    <x-slot name="header">
        {{ __('Reseller Management') }}
    </x-slot>

    {{-- Month/Year Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                    <i class="bi bi-funnel fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Filter Statistics</h6>
                    <small class="text-muted">
                        @if($month === 'all')
                            Showing all-time collections and profit
                        @else
                            Showing statistics for {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                        @endif
                    </small>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.resellers.index') }}" class="d-flex align-items-center gap-2">
                <select name="month" id="month-select" class="form-select form-select-sm rounded-3" style="width:140px;">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" @selected((string)$num === (string)$month)>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" id="year-select" class="form-select form-select-sm rounded-3" style="width:100px;" @disabled($month === 'all')>
                    @foreach($years as $y)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3 fw-semibold">
                    Apply Filter
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleYearSelect() {
            const monthSelect = document.getElementById('month-select');
            const yearSelect = document.getElementById('year-select');
            if (monthSelect && yearSelect) {
                yearSelect.disabled = (monthSelect.value === 'all');
            }
        }
        document.addEventListener('DOMContentLoaded', toggleYearSelect);
        document.addEventListener('livewire:navigated', toggleYearSelect);
        document.addEventListener('DOMContentLoaded', function() {
            const monthSelect = document.getElementById('month-select');
            if (monthSelect) monthSelect.addEventListener('change', toggleYearSelect);
        });
        document.addEventListener('livewire:navigated', function() {
            const monthSelect = document.getElementById('month-select');
            if (monthSelect) monthSelect.addEventListener('change', toggleYearSelect);
        });
    </script>

    {{-- ── Reseller Module KPI Summary ── --}}
    <div class="row g-3 mb-4">
        {{-- Total Collections --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 reseller-kpi-card" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd !important;">
                <div class="card-body p-3 d-flex align-items-center justify-content-between" style="min-height: 85px;">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.8px;">Total Collections</span>
                        <h4 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.5px; font-size: 1.5rem;">৳{{ number_format($resellers->sum('total_collected'), 2) }}</h4>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background: rgba(14, 165, 233, 0.1); color: #0284c7; width: 40px; height: 40px;">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Reseller Profit --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 reseller-kpi-card" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #bbf7d0 !important;">
                <div class="card-body p-3 d-flex align-items-center justify-content-between" style="min-height: 85px;">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.8px;">Resellers Profit</span>
                        <h4 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.5px; font-size: 1.5rem;">৳{{ number_format($resellers->sum('total_profit'), 2) }}</h4>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.1); color: #059669; width: 40px; height: 40px;">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Resellers --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 reseller-kpi-card" style="background: linear-gradient(135deg, #f0fdfa, #ccfbf1); border: 1px solid #99f6e4 !important;">
                <div class="card-body p-3 d-flex align-items-center justify-content-between" style="min-height: 85px;">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.8px;">Active Resellers</span>
                        <h4 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.5px; font-size: 1.5rem;">{{ $resellers->where('status', 'active')->count() }}</h4>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background: rgba(13, 148, 136, 0.1); color: #0d9488; width: 40px; height: 40px;">
                        <i class="bi bi-person-check-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suspended Resellers --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 reseller-kpi-card" style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fecaca !important;">
                <div class="card-body p-3 d-flex align-items-center justify-content-between" style="min-height: 85px;">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.8px;">Suspended</span>
                        <h4 class="fw-extrabold text-dark mb-0" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.5px; font-size: 1.5rem;">{{ $resellers->where('status', 'suspended')->count() }}</h4>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background: rgba(220, 38, 38, 0.1); color: #dc2626; width: 40px; height: 40px;">
                        <i class="bi bi-person-x-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-badge-fill text-primary me-2"></i>All Resellers</h5>
            <a href="{{ route('admin.resellers.create') }}" class="btn btn-sm btn-primary rounded-3"><i class="bi bi-plus-lg me-1"></i>Create Reseller</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email / Phone</th>
                            <th>Company</th>
                            <th class="text-center">Commission</th>
                            <th class="text-end">
                                @if($month === 'all')
                                    Total Collection
                                @else
                                    Collection ({{ $months[$month] }})
                                @endif
                            </th>
                            <th class="text-end">
                                @if($month === 'all')
                                    Total Profit
                                @else
                                    Profit ({{ $months[$month] }})
                                @endif
                            </th>
                            <th class="text-end">Wallet Balance</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resellers as $reseller)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $reseller->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">ID: #{{ $reseller->id }}</small>
                                </td>
                                <td>
                                    <div>{{ $reseller->user->email ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $reseller->phone ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $reseller->company ?? 'N/A' }}</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ $reseller->commission_percentage }}%</span></td>
                                <td class="text-end text-muted">৳{{ number_format($reseller->totalCollections(), 2) }}</td>
                                <td class="text-end fw-bold text-primary">৳{{ number_format($reseller->totalProfit(), 2) }}</td>
                                <td class="text-end fw-bold text-success">৳{{ number_format($reseller->balance, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $reseller->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-2.5 py-1 text-uppercase">
                                        {{ $reseller->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <!-- Adjust Balance Button -->
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $reseller->id }}" title="Adjust Wallet">
                                            <i class="bi bi-cash-coin"></i>
                                        </button>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.resellers.edit', $reseller->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Reseller"><i class="bi bi-pencil-square"></i></a>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.resellers.destroy', $reseller->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reseller? This will permanently delete the associated user account and data.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>

                                    <!-- Adjust Balance Modal -->
                                    <div class="modal fade text-start" id="adjustModal{{ $reseller->id }}" tabindex="-1" aria-labelledby="adjustModalLabel{{ $reseller->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.resellers.adjust-balance', $reseller->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold" id="adjustModalLabel{{ $reseller->id }}">Adjust Wallet: {{ $reseller->user->name }}</h5>
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
                                                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required min="0.01">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Description / Reason</label>
                                                            <textarea name="description" class="form-control" rows="3" placeholder="e.g. Received cash payment from reseller / Withdrawal request" required></textarea>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No resellers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

<style>
    .reseller-kpi-card {
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reseller-kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08) !important;
    }
</style>
</x-app-layout>
