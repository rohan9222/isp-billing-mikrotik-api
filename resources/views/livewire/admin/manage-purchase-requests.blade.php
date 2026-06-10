<div>
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-0" style="color:#1a1f36;">
                    <i class="bi bi-cart-check-fill me-2 text-primary"></i>Package Purchase Requests
                </h4>
                <p class="text-muted small mb-0">Manage customer submissions and applications from the main site</p>
            </div>
        </div>

        {{-- Summary Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1">All Applications</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $counts['all'] }}</h3>
                        </div>
                        <div class="bg-primary-subtle text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-collection-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Pending</span>
                            <h3 class="fw-bold mb-0 text-warning">{{ $counts['pending'] }}</h3>
                        </div>
                        <div class="bg-warning-subtle text-warning rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Contacted</span>
                            <h3 class="fw-bold mb-0 text-info">{{ $counts['contacted'] }}</h3>
                        </div>
                        <div class="bg-info-subtle text-info rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-telephone-outbound-fill fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Completed</span>
                            <h3 class="fw-bold mb-0 text-success">{{ $counts['completed'] }}</h3>
                        </div>
                        <div class="bg-success-subtle text-success rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-check2-circle fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-body py-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted mb-1">Search Customer / Package</label>
                        <div class="position-relative">
                            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live.debounce.300ms="search" type="text" class="form-control form-control-sm ps-5 rounded-pill" placeholder="Search by name, phone, email, package...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-1">Filter by Status</label>
                        <select wire:model.live="statusFilter" class="form-select form-select-sm rounded-pill">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="contacted">Contacted</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fc;">
                            <tr class="small text-muted fw-semibold">
                                <th class="ps-4 py-3">#</th>
                                <th>Date & IP</th>
                                <th>Customer Details</th>
                                <th>Package Requested</th>
                                <th>Installation Address</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr wire:key="req-{{ $req->id }}">
                                <td class="ps-4 text-muted small">{{ $loop->iteration + ($requests->currentPage()-1) * $requests->perPage() }}</td>
                                <td>
                                    <div class="fw-semibold small text-dark">{{ $req->created_at->format('d M Y') }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">IP: {{ $req->ip_address ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold small text-dark">{{ $req->name }}</div>
                                    <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $req->phone }}</div>
                                    @if($req->email)
                                        <div class="text-muted small" style="font-size:0.8rem;"><i class="bi bi-envelope me-1"></i>{{ $req->email }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill small px-2">
                                        {{ $req->package_name }}
                                    </span>
                                    <div class="fw-bold text-dark mt-1" style="font-size: 0.9rem;">৳{{ number_format($req->price, 0) }}</div>
                                </td>
                                <td>
                                    <div class="small text-muted" style="max-width: 250px; white-space: normal; line-height: 1.4;">{{ $req->address }}</div>
                                </td>
                                <td>
                                    @php
                                        $statuses = [
                                            'pending' => ['color' => 'warning', 'label' => 'Pending'],
                                            'contacted' => ['color' => 'info', 'label' => 'Contacted'],
                                            'completed' => ['color' => 'success', 'label' => 'Completed'],
                                            'cancelled' => ['color' => 'secondary', 'label' => 'Cancelled'],
                                        ];
                                        $st = $statuses[$req->status] ?? ['color' => 'secondary', 'label' => 'Unknown'];
                                    @endphp
                                    <span class="badge bg-{{ $st['color'] }}-subtle text-{{ $st['color'] }} border border-{{ $st['color'] }}-subtle rounded-pill small px-2">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button wire:click="openDetailModal({{ $req->id }})"
                                            class="btn btn-sm btn-outline-primary rounded-3 me-1 py-1 px-2" title="View & Edit Details">
                                            <i class="bi bi-eye"></i> View/Notes
                                        </button>
                                        
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-3 py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Status
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item small" href="javascript:void(0)" wire:click="changeStatus({{ $req->id }}, 'pending')"><i class="bi bi-hourglass-split text-warning me-2"></i>Mark Pending</a></li>
                                            <li><a class="dropdown-item small" href="javascript:void(0)" wire:click="changeStatus({{ $req->id }}, 'contacted')"><i class="bi bi-telephone-outbound-fill text-info me-2"></i>Mark Contacted</a></li>
                                            <li><a class="dropdown-item small" href="javascript:void(0)" wire:click="changeStatus({{ $req->id }}, 'completed')"><i class="bi bi-check2-circle text-success me-2"></i>Mark Completed</a></li>
                                            <li><a class="dropdown-item small" href="javascript:void(0)" wire:click="changeStatus({{ $req->id }}, 'cancelled')"><i class="bi bi-x-circle text-secondary me-2"></i>Mark Cancelled</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item small text-danger" href="javascript:void(0)" wire:click="delete({{ $req->id }})" wire:confirm="Delete this purchase application?"><i class="bi bi-trash-fill me-2"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No purchase requests found for the selected filters.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($requests->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedRequestId)
        @php
            $selectedRequest = App\Models\PackagePurchaseRequest::find($selectedRequestId);
        @endphp
        @if($selectedRequest)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.45); z-index:1050;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4 text-dark bg-white">
                    <div class="modal-header border-0 pb-0 px-4 pt-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>Application Details
                        </h5>
                        <button wire:click="$set('showDetailModal', false)" class="btn-close" type="button"></button>
                    </div>
                    <div class="modal-body px-4 pt-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-0">Customer Name</label>
                                <div class="fw-bold fs-6">{{ $selectedRequest->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-0">Phone / Contact</label>
                                <div class="fw-bold fs-6">{{ $selectedRequest->phone }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-0">Email</label>
                                <div class="fw-bold fs-6">{{ $selectedRequest->email ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-0">Requested Package</label>
                                <div class="fw-bold fs-6"><span class="badge bg-primary-subtle text-primary">{{ $selectedRequest->package_name }}</span> (৳{{ number_format($selectedRequest->price, 0) }})</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-muted mb-0">Installation Address</label>
                                <div class="p-3 bg-light rounded-3 small">{{ $selectedRequest->address }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Change Status</label>
                                <select wire:model="selectedRequestStatus" class="form-select rounded-3">
                                    <option value="pending">Pending</option>
                                    <option value="contacted">Contacted</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Internal Remarks & Notes</label>
                                <textarea wire:model="selectedRequestNotes" rows="4" placeholder="Add custom action notes or tracking remarks..." class="form-control rounded-3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button wire:click="$set('showDetailModal', false)" class="btn btn-light rounded-3 px-4">Close</button>
                        <button wire:click="saveDetails" class="btn btn-primary rounded-3 px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
