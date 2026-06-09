<?php

namespace App\Livewire\Reseller;

use App\Models\PackageList;
use Livewire\Component;

class ResellerPackageManagement extends Component
{
    public $packageId = null;

    public $package;

    public $price;

    public $speed;

    public $description;

    public $is_featured = false;
    public $isEditing = false;

    public function rules()
    {
        return [
            'package' => 'required|string|max:50|unique:package_lists,package,'.($this->packageId ?: 'NULL'),
            'price' => 'required|numeric|min:0',
            'speed' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $reseller = auth()->user()->reseller;
        if (! $reseller) {
            flash()->error('Unauthorized.');

            return;
        }

        // Plan label is "reseller name + package"
        $planLabel = auth()->user()->name.' + '.$this->package;

        if ($this->isEditing && $this->packageId) {
            $pkg = PackageList::where('reseller_id', $reseller->id)->findOrFail($this->packageId);
            $pkg->update([
                'package' => $this->package,
                'price' => $this->price,
                'description' => $this->description,
                'speed' => $this->speed,
                'plan_label' => $planLabel,
                'is_featured' => (bool) $this->is_featured,
            ]);
            flash()->success('Custom package updated successfully.');
        } else {
            PackageList::create([
                'package' => $this->package,
                'price' => $this->price,
                'description' => $this->description,
                'speed' => $this->speed,
                'plan_label' => $planLabel,
                'is_featured' => (bool) $this->is_featured,
                'router_name' => null,
                'reseller_id' => $reseller->id,
                'push_to_mikrotik' => false,
            ]);
            flash()->success('Custom package created successfully.');
        }

        $this->resetFields();
    }

    public function edit($id)
    {
        $reseller = auth()->user()->reseller;
        $pkg = PackageList::where('reseller_id', $reseller->id)->findOrFail($id);

        $this->packageId = $pkg->id;
        $this->package = $pkg->package;
        $this->price = $pkg->price;
        $this->speed = $pkg->speed;
        $this->description = $pkg->description;
        $this->is_featured = (bool) $pkg->is_featured;

        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->resetFields();
    }

    public function delete($id)
    {
        $reseller = auth()->user()->reseller;
        $pkg = PackageList::where('reseller_id', $reseller->id)->findOrFail($id);

        try {
            $pkg->delete();
            flash()->success('Custom package deleted successfully.');
        } catch (\Exception $e) {
            flash()->error('Failed to delete package: '.$e->getMessage());
        }
    }

    public function resetFields()
    {
        $this->packageId = null;
        $this->package = '';
        $this->price = '';
        $this->speed = '';
        $this->description = '';
        $this->is_featured = false;
        $this->isEditing = false;
    }

    public function render()
    {
        $reseller = auth()->user()->reseller;

        $assignedPackages = $reseller ? $reseller->assignedPackages : collect();
        $customPackages = $reseller ? PackageList::where('reseller_id', $reseller->id)->get() : collect();

        return view('livewire.reseller.package-management', [
            'assignedPackages' => $assignedPackages,
            'customPackages' => $customPackages,
        ]);
    }
}
