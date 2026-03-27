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
                                    <label class="form-label">Monthly Price (৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" wire:model.defer="price" required>
                                    @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Features <small class="text-muted">(one per line)</small></label>
                                    <textarea class="form-control" wire:model.defer="features_text" rows="5"
                                              placeholder="24 HOURS UNLIMITED&#10;Fiber Optics&#10;OTC Fee - 3000 Taka&#10;24/7 Customer Care"></textarea>
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
                <x-slot name="title">{{ __('Package List') }}</x-slot>
                <x-slot name="aside">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Label</th>
                                    <th>Package</th>
                                    <th>Speed</th>
                                    <th>Price</th>
                                    <th>On Site</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($packages ?? [] as $package)
                                    <tr class="{{ $package->is_featured ? 'table-success' : '' }}">
                                        <td>{{ $package->sort_order }}</td>
                                        <td>
                                            @if($package->plan_label)
                                                <span class="badge bg-primary">{{ $package->plan_label }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $package->package }}</td>
                                        <td>{{ $package->speed }}</td>
                                        <td>{{ number_format($package->price, 0) }}৳</td>
                                        <td>
                                            @if($package->show_on_site)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @can('package-setup-edit')
                                                <button wire:click="editPackage({{ $package->id }})" class="btn btn-info btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endcan
                                            @can('package-setup-delete')
                                                <button wire:click="deletePackage({{ $package->id }})"
                                                        wire:confirm="Delete this package?"
                                                        class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">No packages yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>
