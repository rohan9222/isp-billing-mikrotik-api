<x-dialog-modal wire:model.live="confirmingUser" maxWidth="2xl" class="mt-2">
    <x-slot name="title">
        {{ $userType }}
    </x-slot>

    <x-slot name="content">
        <form wire:submit.prevent="submitUser" method="post">
            <div class="mb-3 row">
                <label for="name" class="col-md-4 col-form-label text-md-end text-start">{{ __('Name') }}</label>
                <div class="col-md-7">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name" wire:model="name" autocomplete="name">
                    <x-error name="name" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="mobile" class="col-md-4 col-form-label text-md-end text-start">{{ __('Mobile') }}</label>
                <div class="col-md-7">
                    <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" placeholder="mobile" wire:model="mobile" autocomplete="mobile" value="880">
                    <x-error name="mobile" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="col-md-4 col-form-label text-md-end text-start">{{ __('Email Address') }}</label>
                <div class="col-md-7">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" wire:model="email" autocomplete="email">
                    <x-error name="email" />
                </div>
            </div>
            
            <div class="mb-3 row">
                <label for="address" class="col-md-4 col-form-label text-md-end text-start">{{ __('Address') }}</label>
                <div class="col-md-7">
                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" wire:model="address"></textarea>
                    <x-error name="address" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password" class="col-md-4 col-form-label text-md-end text-start">{{ __('Password') }}</label>
                <div class="col-md-7">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" wire:model="password" autocomplete="new-password">
                    <x-error name="password" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">{{ __('Confirm Password') }}</label>
                <div class="col-md-7">
                    <input id="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" wire:model="password_confirmation" autocomplete="new-password"> 
                </div>
            </div>

            <x-mikrotik.form-input
                labelClass="col-md-4 col-form-label text-md-end text-start"
                groupClass="col-md-7"
                label="{{ __('Roles') }}"
                type="dropdown"
                :multiple="true"
                name="roles"
                placeholder=''
                required="true"
                :options="$userRoles"
            />

            <div class="mb-3 row">
                <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{ __('Submit') }}">
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <x-button-danger wire:click="$toggle('confirmingUser')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-dang-button>
    </x-slot>
</x-dialog-modal>