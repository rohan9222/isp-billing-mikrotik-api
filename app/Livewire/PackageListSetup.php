<?php

namespace App\Livewire;

use App\Models\PackageList;
use App\Http\Controllers\MikrotikController;
use Livewire\Component;

class PackageListSetup extends Component
{
    public $package_id;
    public $package_name;
    public $price;
    public $description;
    public $plan_label;
    public $speed;
    public $features_text = "24 HOURS UNLIMITED\nFiber Optics\nOTC Fee - 3000 Taka\n24/7 Customer Support";
    public $is_featured        = false;
    public $show_on_site       = true;
    public $sort_order         = 0;
    public $mikrotik_rate_limit;
    public $mikrotik_local_address;
    public $mikrotik_remote_address;
    public $push_to_mikrotik   = false;
    public $mikrotik_pools     = [];
    public $router_name        = null;
    public $packagesData       = [];

    public function mount(): void
    {
        if (! hasAccess(['Super Admin'], ['package-setup'])) {
            abort(403, 'Unauthorized action.');
        }
        $this->reset([
            'package_id', 'package_name', 'price', 'description', 'plan_label', 'speed', 
            'features_text', 'is_featured', 'show_on_site', 'sort_order', 
            'mikrotik_rate_limit', 'mikrotik_local_address', 'mikrotik_remote_address', 'push_to_mikrotik', 'router_name'
        ]);
        $this->show_on_site = true;
        $this->dataRender();
    }

    public function dataRender()
    {
        $this->packagesData = PackageList::with('router')->orderBy('sort_order')->orderBy('price')->get()->toArray();
    }

    protected function rules(): array
    {
        return [
            'package_name' => [
                'required', 'string', 'max:255',
                \Illuminate\Validation\Rule::unique('package_lists', 'package')
                    ->ignore($this->package_id)
                    ->where('router_name', $this->router_name)
            ],
            'router_name'         => 'nullable|string|max:255',
            'price'               => 'required|numeric',
            'description'         => 'nullable|max:255',
            'plan_label'          => 'nullable|max:50',
            'speed'               => 'nullable|max:100',
            'features_text'           => 'nullable|string',
            'sort_order'              => 'nullable|integer|min:0',
            'mikrotik_rate_limit'     => 'nullable|max:100',
            'mikrotik_local_address'  => 'nullable|max:100',
            'mikrotik_remote_address' => 'nullable|max:100',
        ];
    }

    public function updatedRouterName($value): void
    {
        if ($value) {
            $this->loadPools();
        } else {
            $this->mikrotik_pools = [];
        }
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

            $oldName = null;
            if ($this->package_id) {
                $oldName = PackageList::find($this->package_id)?->package;
            }

            PackageList::updateOrCreate(
                ['id' => $this->package_id],
                [
                    'package'                 => $this->package_name,
                    'price'                   => $this->price,
                    'description'             => $this->description,
                    'plan_label'              => $this->plan_label,
                    'speed'                   => $this->speed,
                    'features'                => $features,
                    'is_featured'             => $this->is_featured,
                    'show_on_site'            => $this->show_on_site,
                    'sort_order'              => $this->sort_order ?? 0,
                    'mikrotik_rate_limit'     => $this->mikrotik_rate_limit,
                    'mikrotik_local_address'  => $this->mikrotik_local_address,
                    'mikrotik_remote_address' => $this->mikrotik_remote_address,
                    'push_to_mikrotik'        => $this->push_to_mikrotik,
                    'router_name'             => $this->router_name,
                ]
            );

            // Sync profile to MikroTik routers if toggle is on
            if ($this->push_to_mikrotik && $this->mikrotik_rate_limit) {
                $controller = app(MikrotikController::class);
                if ($oldName && $oldName !== $this->package_name) {
                    $controller->updateProfileOnRouters($oldName, $this->package_name, $this->mikrotik_rate_limit, $this->mikrotik_local_address, $this->mikrotik_remote_address, $this->router_name);
                } else {
                    $controller->pushProfileToRouters($this->package_name, $this->mikrotik_rate_limit, $this->mikrotik_local_address, $this->mikrotik_remote_address, $this->router_name);
                }
            }

            flash()->success('Package saved successfully!');
            $this->reset([
                'package_id', 'package_name', 'price', 'description', 'plan_label', 'speed', 
                'features_text', 'is_featured', 'sort_order', 
                'mikrotik_rate_limit', 'mikrotik_local_address', 'mikrotik_remote_address', 'push_to_mikrotik', 'router_name'
            ]);
            $this->show_on_site = true;
            $this->dataRender();
        } catch (\Exception $e) {
            flash()->error('Error saving data: '.$e->getMessage());
        }
    }

    public function editPackage(int $id): void
    {
        $package = PackageList::findOrFail($id);
        $this->package_id          = $id;
        $this->package_name        = $package->package;
        $this->price               = $package->price;
        $this->description         = $package->description;
        $this->plan_label          = $package->plan_label;
        $this->speed               = $package->speed;
        $this->is_featured             = $package->is_featured;
        $this->show_on_site            = $package->show_on_site;
        $this->sort_order              = $package->sort_order;
        $this->mikrotik_rate_limit     = $package->mikrotik_rate_limit;
        $this->mikrotik_local_address  = $package->mikrotik_local_address;
        $this->mikrotik_remote_address = $package->mikrotik_remote_address;
        $this->push_to_mikrotik        = $package->push_to_mikrotik;
        $this->router_name             = $package->router_name;
        $this->features_text = collect($package->features ?? [])->pluck('value')->implode("\n");
    }

    public function deletePackage(int $id): void
    {
        $package = PackageList::findOrFail($id);

        // Remove from MikroTik routers first if push was enabled
        if ($package->push_to_mikrotik) {
            app(MikrotikController::class)->deleteProfileFromRouters($package->package, $package->router_name);
        }

        $package->delete();
        flash()->success('Package deleted successfully!');
        $this->dataRender();
    }

    public function updateSortOrder($reorder)
    {
        $this->packagesData = collect($reorder)->map(function ($pkg) {
            return collect($this->packagesData)->firstWhere('id', (int) $pkg['value']);
        })->toArray();
        flash()->addInfo('Package List Successfully Reordered. Click "Save Order" to persist.');
    }

    public function saveSortOrder()
    {
        foreach ($this->packagesData as $index => $field) {
            PackageList::where('id', $field['id'])->update(['sort_order' => $index + 1]);
        }
        flash()->success('Package order saved successfully!');
        $this->dataRender();
    }

    public function loadPools(): void
    {
        if (empty($this->router_name)) {
            flash()->warning('Please select a specific router first to load its IP pools.');
            return;
        }

        try {
            $routersPools = app(MikrotikController::class)->routerList(
                $this->router_name,
                '/ip/pool/print',
                '/ip pool print without-paging terse'
            );

            $uniquePools = [];
            foreach ($routersPools as $routerName => $pools) {
                if (is_array($pools)) {
                    foreach ($pools as $pool) {
                        if (isset($pool['name'])) {
                            $uniquePools[] = $pool['name'];
                        }
                    }
                } else {
                    // It might be an error string from checkConnection
                    if (is_string($pools) && str_starts_with($pools, 'Error:')) {
                        flash()->error("$routerName: $pools");
                    }
                }
            }
            
            $this->mikrotik_pools = array_unique($uniquePools);
            if (empty($this->mikrotik_pools)) {
                flash()->warning('No IP pools found on the selected router.');
            } else {
                flash()->success('Loaded ' . count($this->mikrotik_pools) . ' pool(s) from ' . array_key_first($routersPools) . '.');
            }
        } catch (\Exception $e) {
            flash()->error('Error loading pools: ' . $e->getMessage());
        }
    }

    public function syncFromMikrotik(): void
    {
        try {
            $profiles = app(MikrotikController::class)->routerList(
                null,
                '/ppp/profile/print',
                '/ppp profile print without-paging terse'
            );

            $synced = 0;
            $errors = [];

            foreach ($profiles as $routerName => $routerProfiles) {
                if (!is_array($routerProfiles)) {
                    $errors[] = "$routerName: $routerProfiles";
                    continue;
                }

                foreach ($routerProfiles as $profile) {
                    $name = $profile['name'] ?? null;
                    if (!$name || $name === 'default' || $name === 'default-encryption') {
                        continue; // skip built-in profiles
                    }

                    // Parse rate-limit to a human-readable speed string
                    $rateLimit = $profile['rate-limit'] ?? null;
                    $speed = null;
                    if ($rateLimit) {
                        // Format: "8M/8M" or "8192000/8192000"
                        $parts = explode('/', $rateLimit);
                        $upload = trim($parts[0] ?? '');
                        $download = trim($parts[1] ?? $upload);
                        // Convert bps numbers to Mbps if not already string
                        $speed = is_numeric($upload)
                            ? round($upload / 1048576, 1) . '/' . round($download / 1048576, 1) . ' Mbps'
                            : "$upload / $download";
                    }

                    $localAddress = $profile['local-address'] ?? null;
                    $remoteAddress = $profile['remote-address'] ?? null;

                    $existing = PackageList::where('package', $name)->where('router_name', $routerName)->first();

                    PackageList::updateOrCreate(
                        ['package' => $name, 'router_name' => $routerName],
                        [
                            // Auto-fill speed/address only if not already set
                            'speed'                   => $existing?->speed ?? $speed,
                            'mikrotik_rate_limit'     => $existing?->mikrotik_rate_limit ?? $rateLimit,
                            'mikrotik_local_address'  => $existing?->mikrotik_local_address ?? $localAddress,
                            'mikrotik_remote_address' => $existing?->mikrotik_remote_address ?? $remoteAddress,
                            
                            // Preserve existing price/features if already set
                            'price'                   => $existing?->price ?? 0,
                            'description'             => $existing?->description ?? null,
                            'plan_label'              => $existing?->plan_label ?? null,
                            'features'                => $existing?->features ?? [],
                            'is_featured'             => $existing?->is_featured ?? false,
                            'show_on_site'            => $existing?->show_on_site ?? false,
                            'sort_order'              => $existing?->sort_order ?? 0,
                            'push_to_mikrotik'        => $existing?->push_to_mikrotik ?? false,
                        ]
                    );

                    $synced++;
                }
            }

            if (!empty($errors)) {
                flash()->warning('Sync done with some errors: ' . implode(', ', $errors));
            } else {
                flash()->success("$synced profile(s) synced from MikroTik successfully!");
            }
            $this->dataRender();
        } catch (\Exception $e) {
            flash()->error('Sync failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $routers  = \App\Models\RouterList::where('action', 'connected')->get();
        return view('livewire.package-list-setup', compact('routers'))->layout('layouts.app');
    }
}