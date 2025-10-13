<div class="zoom-in">
    <x-slot name="header">
        {{ __('Full Site Settings') }}
    </x-slot>
    <div class="card p-2">
        <div class="col-12">
            <x-form-section class="mb-3 " submit="updateSettings">
                <x-slot name="title">{{ __('Site Settings') }}</x-slot>
                <x-slot name="description">{{ __('Update your site settings here.') }}</x-slot>

                <x-slot name="form">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="site_name">Site Name</label>
                                <input type="text" id="site_name" class="form-control" placeholder="Name" wire:model='site_name'
                                    name="name">
                                @error('site_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="site_title">Site title</label>
                                <input type="text" id="site_title" class="form-control" placeholder="Site title" wire:model='site_title'
                                    name="site_title">
                                @error('site_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" class="form-control" placeholder="Enter your email" wire:model='site_email'
                                    name="email">
                                @error('site_email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" id="phone" class="form-control" placeholder="Enter your phone" wire:model='site_phone'
                                    name="phone">
                                @error('site_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="site_logo">Site Logo</label>
                                <input type="file" id="site_logo" class="form-control" wire:model='site_logo' name="site_logo">
                                @error('site_logo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div wire:loading wire:target="site_logo">Uploading...</div>
                            <div class="col-3">
                                @if ($site_logo)
                                    <label class="form-label" for="upload_site_logo">Photo Preview:</label>
                                    <img id="upload_site_logo" src="{{ $site_logo->temporaryUrl() }}" alt="Image Preview"
                                        class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button"
                                        class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto('logo')"><i
                                            class="bi bi-x-circle-fill"></i></button>
                                @elseif($preview_site_logo)
                                    <label class="form-label" for="upload_site_logo">Photo Preview:</label>
                                    <img id="upload_site_logo" src="{{ asset($preview_site_logo) }}" alt="Image Preview"
                                        class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button"
                                        class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePreviewPhoto('logo')"><i
                                            class="bi bi-x-circle-fill"></i></button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="icon">Site Icon</label>
                                <input type="file" id="icon" class="form-control" wire:model='site_icon' name="icon">
                                @error('site_icon')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div wire:loading wire:target="site_icon">Uploading...</div>
                            <div class="col-3">
                                @if ($site_icon)
                                    <label class="form-label" for="upload_site_icon">Photo Preview:</label>
                                    <img id="upload_site_icon" src="{{ $site_icon->temporaryUrl() ?? asset($site_icon) }}"
                                        alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button
                                        type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto('icon')"><i
                                            class="bi bi-x-circle-fill"></i></button>
                                @elseif($preview_site_icon)
                                    <label class="form-label" for="upload_site_icon">Photo Preview:</label>
                                    <img id="upload_site_icon" src="{{ asset($preview_site_icon) }}" alt="Image Preview"
                                        class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button"
                                        class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePreviewPhoto('icon')"><i
                                            class="bi bi-x-circle-fill"></i></button>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="favicon">Site Favicon</label>
                                <input type="file" id="favicon" class="form-control" wire:model='site_favicon' name="favicon">
                                @error('site_favicon')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div wire:loading wire:target="site_favicon">Uploading...</div>
                            <div class="col-3">
                                @if ($site_favicon)
                                    <label class="form-label" for="upload_site_favicon">Photo Preview:</label>
                                    <img id="upload_site_favicon" src="{{ $site_favicon->temporaryUrl() ?? asset($site_favicon) }}"
                                        alt="Image Preview" class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button
                                        type="button" class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePhoto('favicon')"><i
                                            class="bi bi-x-circle-fill"></i></button>
                                @elseif($preview_site_favicon)
                                    <label class="form-label" for="upload_site_favicon">Photo Preview:</label>
                                    <img id="upload_site_favicon" src="{{ asset($preview_site_favicon) }}" alt="Image Preview"
                                        class="img-fluid img-thumbnail" style="max-width: 200px; max-height: 200px;"><button type="button"
                                        class="btn btn-white btn-sm text-danger mx-2 fs-4" wire:click="removePreviewPhoto('favicon')"><i
                                            class="bi bi-x-circle-fill"></i></button>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="last-name-column">Address</label>
                                <input type="text" id="last-name-column" class="form-control" placeholder="Address"
                                    wire:model='site_address' name="address">
                                @error('site_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="country-floating">Invoice Prefix</label>
                                <input type="text" id="country-floating" class="form-control" name="prefix" placeholder="Invoice Prefix"
                                    wire:model='site_invoice_prefix'>
                                @error('site_invoice_prefix')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="disable_check_no">How many time user can disable in a month</label>
                                <input type="number" id="disable_check_no" class="form-control" name="prefix" placeholder="number"
                                    wire:model='disable_check_no'>
                                @error('disable_check_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="disable_check_days">How many days after user can disable from disable date.</label>
                                <input type="text" id="disable_check_days" class="form-control" name="prefix" placeholder="Days"
                                    wire:model='disable_check_days'>
                                @error('disable_check_days')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </x-slot>
                <x-slot name="actions">
                    <x-action-message class="me-3" on="saved">
                        {{ __('Saved.') }}
                    </x-action-message>

                    <x-button-success class="btn-md" wire:loading.attr="disabled" wire:target="updateSettings">
                        {{ __('Save') }}
                    </x-button-success>
                </x-slot>
            </x-form-section>
        </div>
    </div>
</div>
