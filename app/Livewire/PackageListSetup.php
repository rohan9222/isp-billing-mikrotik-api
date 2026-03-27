<?php

namespace App\Livewire;

use App\Models\PackageList;
use Livewire\Component;

class PackageListSetup extends Component
{
    public $package_id;
    public $package_name;
    public $price;
    public $description;
    public $plan_label;
    public $speed;
    public $features_text; // newline-separated features for easy editing
    public $is_featured   = false;
    public $show_on_site  = true;
    public $sort_order    = 0;

    public function mount(): void
    {
        if (! hasAccess(['Super Admin'], ['package-setup'])) {
            abort(403, 'Unauthorized action.');
        }
        $this->reset(['package_id','package_name','price','description','plan_label','speed','features_text','is_featured','show_on_site','sort_order']);
        $this->show_on_site = true;
    }

    protected function rules(): array
    {
        return [
            'package_name' => 'required|string|max:255|unique:package_lists,package,'.$this->package_id,
            'price'        => 'required|numeric',
            'description'  => 'nullable|max:255',
            'plan_label'   => 'nullable|max:50',
            'speed'        => 'nullable|max:100',
            'features_text' => 'nullable|string',
            'sort_order'   => 'nullable|integer|min:0',
        ];
    }

    public function createPackage(): void
    {
        $this->validate();

        try {
            // Convert newline-separated features to JSON array
            $features = collect(explode("\n", $this->features_text ?? ''))
                ->map(fn($f) => ['value' => trim($f)])
                ->filter(fn($f) => ! empty($f['value']))
                ->values()
                ->toArray();

            PackageList::updateOrCreate(
                ['id' => $this->package_id],
                [
                    'package'      => $this->package_name,
                    'price'        => $this->price,
                    'description'  => $this->description,
                    'plan_label'   => $this->plan_label,
                    'speed'        => $this->speed,
                    'features'     => $features,
                    'is_featured'  => $this->is_featured,
                    'show_on_site' => $this->show_on_site,
                    'sort_order'   => $this->sort_order ?? 0,
                ]
            );

            flash()->success('Package saved successfully!');
            $this->reset(['package_id','package_name','price','description','plan_label','speed','features_text','is_featured','sort_order']);
            $this->show_on_site = true;
        } catch (\Exception $e) {
            flash()->error('Error saving data: '.$e->getMessage());
        }
    }

    public function editPackage(int $id): void
    {
        $package = PackageList::findOrFail($id);
        $this->package_id    = $id;
        $this->package_name  = $package->package;
        $this->price         = $package->price;
        $this->description   = $package->description;
        $this->plan_label    = $package->plan_label;
        $this->speed         = $package->speed;
        $this->is_featured   = $package->is_featured;
        $this->show_on_site  = $package->show_on_site;
        $this->sort_order    = $package->sort_order;
        // Convert features JSON to newline text for editing
        $this->features_text = collect($package->features ?? [])->pluck('value')->implode("\n");
    }

    public function deletePackage(int $id): void
    {
        PackageList::findOrFail($id)->delete();
        flash()->success('Package deleted successfully!');
    }

    public function render()
    {
        $packages = PackageList::orderBy('sort_order')->orderBy('price')->get();
        return view('livewire.package-list-setup', compact('packages'))->layout('layouts.app');
    }
}