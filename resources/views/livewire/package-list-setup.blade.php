<div class="zoom-in">
    <x-slot name="header">
        {{ __('Packages Setup') }}
    </x-slot>
    <div class="row g-2 d-flex justify-content-center">
        <div class="col-lg-4 col-md-5 col-sm-12">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Create Package') }}</x-slot>
                <x-slot name="aside">
                    @if(auth()->user()->can('package-setup-create') || $package_id)
                        <form class="form-control" wire:submit.prevent="createPackage">
                            <x-mikrotik.form-group
                                column="col-12"
                                label="Package Name"
                                name="package_name"
                                type="text"
                                required="true"
                            />
                            <x-mikrotik.form-group
                                column="col-12"
                                label="Package Price"
                                name="price"
                                type="text"
                                required="true"
                            />
                            <x-mikrotik.form-group
                                column="col-12"
                                label="Package Description"
                                name="description"
                                type="text"
                            />
                            <button type="submit" class="mt-3 btn btn-primary">Save</button>
                        </form>
                    @else
                        <form class="disabled-form">
                            <fieldset disabled>
                                <div class="form-group">
                                    <label for="package_name">Package Name</label>
                                    <input type="text" class="form-control" id="package_name">
                                    @error('package_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="price">Package Price</label>
                                    <input type="text" class="form-control" id="price" >
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Package Description</label>
                                    <input type="text" class="form-control" id="description">
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button class="mt-3 btn btn-primary">Save</button>
                            </fieldset>
                        </form>
                    @endcan
                </x-slot>
            </x-mikrotik.section-form>
        </div>
        <div class="col-lg-6 col-md-7 col-sm-12">
            <x-mikrotik.section-form>
                <x-slot name="title">{{ __('Package List') }}</x-slot>
                <x-slot name="aside">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Package Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages ?? [] as $package)
                                <tr>
                                    <td>{{ $package->id }}</td>
                                    <td>{{ $package->package }}</td>
                                    <td>{{ $package->price }}</td>
                                    <td>{{ $package->description }}</td>
                                    <td>
                                        @can('package-setup-edit')
                                            <button wire:click="editPackage({{ $package->id }})" class="btn btn-info btn-sm"><i class="bi bi-pencil-square"></i></button>
                                        @else
                                            <button class="btn btn-info btn-sm disabled"><i class="bi bi-pencil-square"></i></button>
                                        @endcan

                                        @can('package-setup-delete')
                                            <button wire:click="deletePackage({{ $package->id }})" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="bi bi-trash"></i></button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-slot>
            </x-mikrotik.section-form>
        </div>
    </div>
</div>
