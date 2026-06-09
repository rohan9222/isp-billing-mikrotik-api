<?php

namespace App\Livewire\Reseller;

use App\Models\PackageList;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ResellerVoucherManagement extends Component
{
    use WithPagination;

    public $count = 5;

    public $type = 'fixed_amount';

    public $value;

    public $package_id;

    public $expiry_date;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->expiry_date = now()->addMonths(3)->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'count' => 'required|integer|min:1|max:100',
            'type' => 'required|in:fixed_amount,package_based',
            'value' => 'required_if:type,fixed_amount|nullable|numeric|min:1',
            'package_id' => 'required_if:type,package_based|nullable|exists:package_lists,id',
            'expiry_date' => 'required|date|after:today',
        ];
    }

    public function generate()
    {
        $this->validate();

        $reseller = auth()->user()->reseller;
        if (! $reseller) {
            flash()->error('Unauthorized.');

            return;
        }

        $voucherValue = 0.00;
        $packageId = null;

        if ($this->type === 'package_based') {
            $package = PackageList::findOrFail($this->package_id);

            // Verify package belongs to reseller
            $isAssigned = $reseller->assignedPackages->contains($package->id);
            $isCustom = $package->reseller_id === $reseller->id;
            if (! $isAssigned && ! $isCustom) {
                $this->addError('package_id', 'Selected package is not assigned to you.');

                return;
            }

            $voucherValue = $package->price;
            $packageId = $package->id;
        } else {
            $voucherValue = (float) $this->value;
        }

        $generatedCount = 0;

        for ($i = 0; $i < $this->count; $i++) {
            do {
                $code = 'VCH-'.strtoupper(Str::random(6)).'-'.strtoupper(Str::random(6));
            } while (Voucher::where('code', $code)->exists());

            Voucher::create([
                'code' => $code,
                'value' => $voucherValue,
                'type' => $this->type,
                'package_id' => $packageId,
                'status' => 'unused',
                'expiry_date' => $this->expiry_date,
                'reseller_id' => $reseller->id,
            ]);

            $generatedCount++;
        }

        flash()->success("Successfully generated {$generatedCount} vouchers.");

        $this->count = 5;
        $this->value = null;
        $this->package_id = null;
        $this->expiry_date = now()->addMonths(3)->format('Y-m-d');

        $this->resetPage();
    }

    public function render()
    {
        $reseller = auth()->user()->reseller;
        if (! $reseller) {
            abort(403);
        }

        $vouchers = Voucher::where('reseller_id', $reseller->id)
            ->with(['package', 'usedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $packages = PackageList::where(function ($q) use ($reseller) {
            $q->whereIn('id', $reseller->assignedPackages->pluck('id'))
                ->orWhere('reseller_id', $reseller->id);
        })
            ->get();

        return view('livewire.reseller.voucher-management', [
            'vouchers' => $vouchers,
            'packages' => $packages,
        ])->layout('layouts.app');
    }
}
