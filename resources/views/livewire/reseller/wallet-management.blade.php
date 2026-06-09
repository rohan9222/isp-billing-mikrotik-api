<div class="row">
    <!-- Balance Cards -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white;">
            <div class="card-body d-flex flex-column justify-content-between p-4">
                <div>
                    <h6 class="text-uppercase fw-bold mb-2 text-white-50" style="font-size: 0.75rem; letter-spacing: 1px;">Wallet Balance</h6>
                    <h1 class="fw-bold mb-1">৳{{ number_format($reseller->balance, 2) }}</h1>
                    <p class="small text-white-50 mb-0">Reseller Account ID: #{{ $reseller->id }}</p>
                </div>
                
                <div class="mt-4 pt-3 border-top border-white border-opacity-10 small text-white-50">
                    Commission payouts and adjustments are reflected automatically in your balance.
                </div>
            </div>
        </div>
    </div>

    <!-- Ledger Column -->
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-wallet2 text-success me-2"></i>Transaction Ledger</h5>
                
                <!-- Filter -->
                <select wire:model.live="type" class="form-select form-select-sm" style="max-width: 150px;">
                    <option value="all">All Transactions</option>
                    <option value="credit">Credit Only</option>
                    <option value="debit">Debit Only</option>
                </select>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Description</th>
                                <th>Reference Type</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr>
                                    <td class="small text-muted ps-3">{{ $trx->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="text-dark small fw-semibold">{{ $trx->description }}</td>
                                    <td>
                                        @if($trx->reference_type)
                                            <span class="badge bg-secondary-subtle text-secondary text-xs">{{ $trx->reference_type }} (#{{ $trx->reference_id }})</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">৳{{ number_format($trx->amount, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $trx->type === 'credit' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-2 py-0.5 text-uppercase text-xs">
                                            {{ $trx->type }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No wallet transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
