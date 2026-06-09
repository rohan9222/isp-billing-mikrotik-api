<div>
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-0" style="color:#1a1f36;">
                    <i class="bi bi-wallet2 me-2 text-danger"></i>Expense Management
                </h4>
                <p class="text-muted small mb-0">Track all ISP operating costs</p>
            </div>
            <button wire:click="openCreate"
                class="btn btn-danger rounded-3 px-4 fw-semibold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Expense
            </button>
        </div>

        {{-- Filter Bar --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body py-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Category</label>
                        <select wire:model.live="filterCategory" class="form-select form-select-sm rounded-3">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Month</label>
                        <select wire:model.live="filterMonth" class="form-select form-select-sm rounded-3">
                            <option value="">All Months</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Year</label>
                        <select wire:model.live="filterYear" class="form-select form-select-sm rounded-3">
                            <option value="">All Years</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        {{-- Category totals pills --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            @foreach($categories as $key => $label)
                                @if(isset($totals[$key]) && $totals[$key] > 0)
                                    @php
                                        $colors = ['item_purchase'=>'primary','raw_bill'=>'warning','employee_salary'=>'info','miscellaneous'=>'secondary'];
                                        $c = $colors[$key] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $c }}-subtle text-{{ $c }} border border-{{ $c }}-subtle rounded-pill px-3 py-2 small fw-semibold">
                                        {{ $label }}: ৳{{ number_format($totals[$key], 2) }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grand Total Banner --}}
        @if($grandTotal > 0)
        <div class="alert rounded-4 border-0 mb-4 py-3 px-4 d-flex align-items-center"
             style="background: linear-gradient(135deg,#fff0f0,#ffe5e5); border-left: 4px solid #ef4444 !important;">
            <i class="bi bi-exclamation-circle-fill text-danger fs-5 me-3"></i>
            <div>
                <span class="fw-semibold text-dark">Total Expenses (current filter):</span>
                <span class="fw-bold text-danger fs-5 ms-2">৳{{ number_format($grandTotal, 2) }}</span>
            </div>
        </div>
        @endif

        {{-- Expense Table --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fc;">
                            <tr class="small text-muted fw-semibold">
                                <th class="ps-4 py-3">#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Reference</th>
                                <th>Added By</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr wire:key="exp-{{ $expense->id }}">
                                <td class="ps-4 text-muted small">{{ $loop->iteration + ($expenses->currentPage()-1) * $expenses->perPage() }}</td>
                                <td class="small fw-semibold">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $colors = ['item_purchase'=>'primary','raw_bill'=>'warning','employee_salary'=>'info','miscellaneous'=>'secondary'];
                                        $c = $colors[$expense->category] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $c }}-subtle text-{{ $c }} border border-{{ $c }}-subtle rounded-pill small px-2">
                                        {{ $expense->category_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold small text-dark">{{ $expense->title }}</div>
                                    @if($expense->description)
                                        <div class="text-muted" style="font-size:0.75rem;">{{ Str::limit($expense->description, 60) }}</div>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $expense->reference_no ?? '—' }}</td>
                                <td class="small text-muted">{{ $expense->addedBy?->name ?? '—' }}</td>
                                <td class="text-end fw-bold text-danger">৳{{ number_format($expense->amount, 2) }}</td>
                                <td class="text-end pe-4">
                                    <button wire:click="openEdit({{ $expense->id }})"
                                        class="btn btn-sm btn-outline-primary rounded-3 me-1 py-1 px-2" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button wire:click="delete({{ $expense->id }})"
                                        wire:confirm="Delete this expense?"
                                        class="btn btn-sm btn-outline-danger rounded-3 py-1 px-2" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No expenses found for the selected filters.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($expenses->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $expenses->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Add / Edit Modal ─────────────────────────────────────── --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.45);">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-{{ $editId ? 'pencil-square text-primary' : 'plus-circle text-danger' }} me-2"></i>
                        {{ $editId ? 'Edit Expense' : 'Add New Expense' }}
                    </h5>
                    <button wire:click="$set('showModal', false)" class="btn-close" type="button"></button>
                </div>
                <div class="modal-body px-4 pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select wire:model="category" class="form-select rounded-3 @error('category') is-invalid @enderror">
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Expense Date <span class="text-danger">*</span></label>
                            <input wire:model="expense_date" type="date" class="form-control rounded-3 @error('expense_date') is-invalid @enderror">
                            @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Title <span class="text-danger">*</span></label>
                            <input wire:model="title" type="text" placeholder="e.g. Office electricity bill"
                                class="form-control rounded-3 @error('title') is-invalid @enderror">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Amount (৳) <span class="text-danger">*</span></label>
                            <input wire:model="amount" type="number" step="0.01" min="0" placeholder="0.00"
                                class="form-control rounded-3 @error('amount') is-invalid @enderror">
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Reference No</label>
                            <input wire:model="reference_no" type="text" placeholder="Invoice / receipt number"
                                class="form-control rounded-3">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Description</label>
                            <textarea wire:model="description" rows="2" placeholder="Additional notes…"
                                class="form-control rounded-3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button wire:click="$set('showModal', false)" class="btn btn-light rounded-3 px-4">Cancel</button>
                    <button wire:click="save" class="btn btn-danger rounded-3 px-4 fw-semibold">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        {{ $editId ? 'Update Expense' : 'Save Expense' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
