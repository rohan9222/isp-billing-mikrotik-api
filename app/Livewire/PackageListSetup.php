<?php

namespace App\Livewire;

use App\Models\PackageList;
use Livewire\Component;

class PackageListSetup extends Component
{
    public $package_name;

    public $price;

    public $description;

    public $package_id;
    // ['package_name', 'price', 'description', 'merchant_company']

    public function mount()
    {
        if (! hasAccess(['Super Admin'], ['package-setup'])) {
            abort(403, 'Unauthorized action.');
        }

        // Initialize properties
        $this->reset();
    }
    
    protected function rules()
    {
        return [
            'package_name' => 'required|string|max:255|unique:package_lists,package,'.$this->package_id,
            'price' => 'required|numeric',
            'description' => 'max:255',
        ];
    }

    public function createPackage()
    {
        // Validate the input data
        $this->validate();

        try {
            // Save or update the package data
            PackageList::updateOrCreate(
                ['id' => $this->package_id],
                [
                    'package' => $this->package_name,
                    'price' => $this->price,
                    'description' => $this->description,
                ]
            );
            flash()->success('Data saved successfully!');
            // Reset the form data
            $this->reset();
        } catch (\Exception $e) {
            flash()->error('Error saving data: '.$e->getMessage());
        }
    }

    public function editPackage($id)
    {
        $package = PackageList::find($id);
        $this->package_id = $id;
        $this->package_name = $package->package;
        $this->price = $package->price;
        $this->description = $package->description;
    }

    public function deletePackage($id)
    {
        $package = PackageList::find($id);
        $package->delete();
        flash()->success('Package deleted successfully!');
    }

    public function render()
    {
        $packages = PackageList::latest()->get();

        return view('livewire.package-list-setup', compact('packages'))->layout('layouts.app');
    }
}