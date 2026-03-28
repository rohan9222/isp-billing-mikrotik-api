<div class="zoom-in">
    <x-slot name="header">
        {{ __('Packages Setup') }}
    </x-slot>
    <div class="row g-2 d-flex justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-12">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ $package_id ? __('Edit Package') : __('Create Package') }}</x-slot>
                <x-slot name="aside">
                    @if(auth()->user()->can('package-setup-create') || $package_id)
                        <form class="form-control border-0" wire:submit.prevent="createPackage">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Plan Label <small class="text-muted">(e.g. MINOR)</small></label>
                                    <input type="text" class="form-control" wire:model.defer="plan_label" placeholder="MINOR / JUNIOR / BASIC">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model.defer="package_name" required>
                                    @error('package_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Speed <small class="text-muted">(e.g. 8 Mbps)</small></label>
                                    <input type="text" class="form-control" wire:model.defer="speed" placeholder="8 Mbps">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        MikroTik Rate Limit
                                        <small class="text-muted">(e.g. 8M/8M)</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-router"></i></span>
                                        <input type="text" class="form-control" wire:model.defer="mikrotik_rate_limit" placeholder="8M/8M or 512k/1M">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Select Router <small class="text-muted">(Optional)</small>
                                    </label>
                                    <select class="form-select @error('router_name') is-invalid @enderror" wire:model.live="router_name">
                                        <option value="">-- Apply to All Routers --</option>
                                        @foreach($routers as $router)
                                            <option value="{{ $router->router_name }}">{{ $router->router_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('router_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Local Address
                                        <small class="text-muted">(e.g. 192.168.1.1)</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo"></i></span>
                                        <input type="text" list="pool-list" class="form-control" wire:model.defer="mikrotik_local_address" placeholder="IP Address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Remote Address/Pool
                                        <small class="text-muted">(e.g. pool1)</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                                        <input type="text" list="pool-list" class="form-control" wire:model.defer="mikrotik_remote_address" placeholder="Pool Name or IP">
                                        <button class="btn btn-outline-secondary" type="button" wire:click="loadPools" title="Load Router Pools">
                                            <i class="bi bi-arrow-clockwise" wire:loading.remove wire:target="loadPools"></i>
                                            <span class="spinner-border spinner-border-sm" wire:loading wire:target="loadPools"></span>
                                        </button>
                                    </div>
                                    <datalist id="pool-list">
                                        @foreach($mikrotik_pools as $pool)
                                            <option value="{{ $pool }}">{{ $pool }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Monthly Price (৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="price" required>
                                    @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Features <small class="text-muted">(one per line)</small></label>
                                    <textarea class="form-control" wire:model.defer="features_text" rows="5" placeholder="24 HOURS UNLIMITED&#10;Fiber Optics&#10;OTC Fee - 3000 Taka&#10;24/7 Customer Care"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description <small class="text-muted">(optional short note)</small></label>
                                    <input type="text" class="form-control" wire:model.defer="description">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" wire:model.defer="sort_order" min="0">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" wire:model.defer="show_on_site" id="show_on_site">
                                        <label class="form-check-label" for="show_on_site">Show on Site</label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" wire:model.defer="is_featured" id="is_featured">
                                        <label class="form-check-label" for="is_featured">Featured</label>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" wire:model.defer="push_to_mikrotik" id="push_to_mikrotik">
                                        <label class="form-check-label" for="push_to_mikrotik">
                                            <i class="bi bi-router text-danger"></i> Push to MikroTik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-save"></i> Save
                                    </button>
                                    @if($package_id)
                                        <button type="button" wire:click="$set('package_id', null)" class="btn btn-secondary">
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-muted">You don't have permission to create packages.</p>
                    @endif
                </x-slot>
            </x-mikrotik.section-form>
        </div>

        <div class="col-lg-7 col-md-6 col-sm-12">
            <x-mikrotik.section-form>
                <x-slot name="title">
                    <div class="d-flex align-items-center justify-content-between">
                        <span>{{ __('Package List') }}</span>
                        <button wire:click="saveSortOrder" class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-save"></i> Save Order
                        </button>
                        <button wire:click="syncFromMikrotik"
                                wire:loading.attr="disabled"
                                class="btn btn-sm btn-outline-success">
                            <span wire:loading.remove wire:target="syncFromMikrotik">
                                <i class="bi bi-arrow-repeat"></i> Sync from MikroTik
                            </span>
                            <span wire:loading wire:target="syncFromMikrotik">
                                <span class="spinner-border spinner-border-sm"></span> Syncing...
                            </span>
                        </button>
                    </div>
                </x-slot>
                <x-slot name="aside">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Label</th>
                                    <th>Package</th>
                                    <th>Router</th>
                                    <th>Speed</th>
                                    <th>Rate Limit</th>
                                    <th>Price</th>
                                    <th>On Site</th>
                                    <th><i class="bi bi-router"></i></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody wire:sortable="updateSortOrder">
                                @forelse ($packagesData ?? [] as $package)
                                    <tr wire:sortable.item="{{ $package['id'] }}" wire:key="pkg-{{ $package['id'] }}" class="{{ $package['is_featured'] ? 'table-success' : '' }}">
                                        <td>
                                            <i wire:sortable.handle class="bi bi-dpad-fill text-muted me-1" style="cursor: grab;"></i>
                                            {{ $package['sort_order'] }}
                                        </td>
                                        <td>
                                            @if($package['plan_label'])
                                                <span class="badge bg-primary">{{ $package['plan_label'] }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $package['package'] }}</td>
                                        <td>
                                            @if($package['router'])
                                                <span class="badge bg-secondary"><i class="bi bi-router"></i> {{ $package['router']['router_name'] }}</span>
                                            @else
                                                <span class="badge bg-light text-dark border">All Routers</span>
                                            @endif
                                        </td>
                                        <td>{{ $package['speed'] }}</td>
                                        <td>
                                            @if($package['mikrotik_rate_limit'])
                                                <code class="text-danger small">{{ $package['mikrotik_rate_limit'] }}</code>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($package['price'], 0) }}৳</td>
                                        <td>
                                            @if($package['show_on_site'])
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($package['push_to_mikrotik'])
                                                <i class="bi bi-router text-success" title="Synced to MikroTik"></i>
                                            @else
                                                <i class="bi bi-router text-muted" title="Not pushed to MikroTik"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @can('package-setup-edit')
                                                <button wire:click="editPackage({{ $package['id'] }})" class="btn btn-info btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endcan
                                            @can('package-setup-delete')
                                                <button wire:click="deletePackage({{ $package['id'] }})"
                                                        wire:confirm="Delete this package?"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center text-muted">No packages yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>
