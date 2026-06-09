<?php

namespace App\Livewire\Reseller;

use App\Models\ResellerWalletTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class ResellerWalletManagement extends Component
{
    use WithPagination;

    public $type = 'all';

    protected $paginationTheme = 'bootstrap';

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $reseller = auth()->user()->reseller;
        if (! $reseller) {
            abort(403);
        }

        $transactions = ResellerWalletTransaction::where('reseller_id', $reseller->id)
            ->when($this->type !== 'all', function ($q) {
                $q->where('type', $this->type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.reseller.wallet-management', [
            'reseller' => $reseller,
            'transactions' => $transactions,
        ])->layout('layouts.app');
    }
}
