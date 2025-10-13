<x-dialog-modal wire:model.live="confirmingRole" maxWidth="2xl" class="mt-2">
    <x-slot name="title">
        {{ $roleType }}
    </x-slot>

    <x-slot name="content">
        <form wire:submit.prevent="saveRole" method="post">
            <x-mikrotik.form-input
                labelClass="col-md-4 col-form-label text-md-end text-start"
                groupClass="col-md-7"
                label="{{ __('Role Name') }}"
                type="text"
                name="name"
                required="true"
            />
            <x-mikrotik.form-input
                labelClass="col-md-4 col-form-label text-md-end text-start"
                groupClass="col-md-7"
                label="{{ __('Permissions') }}"
                type="dropdown"
                :multiple="true"
                inputStyle="height: 210px;"
                name="permissions"
                placeholder=''
                required="true"
                :options="$permissionList ? $permissionList->pluck('name')->toArray() : []"
            />
            <a class="icon-link icon-link-hover col-md-4 offset-md-4" style="--bs-icon-link-transform: translate3d(0, -.125rem, 0); --bs-link-hover-color-rgb: 25, 135, 84;" href="" data-bs-toggle="collapse" data-bs-target="#collapseHelp" aria-expanded="false" aria-controls="collapseHelp">
                <i class="bi bi-question-circle-fill"></i>
                {{ __('Help') }}
            </a>
            <div class="mb-3 col-11">
                <div class="collapse collapse-vertical" id="collapseHelp">
                    <div class="card card-body">
                        <p><i class="bi bi-star-fill text-success"></i> {{ __('If You are select multiple permission then this role will have all the permissions') }}</p>
                        <p><i class="bi bi-star-fill text-success"></i> {{ __('If You are select single permission then this role will have only that permission') }}</p>
                        <p><i class="bi bi-star-fill text-success"></i> {{ __('For multiple permission select, please use ctrl key') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <x-button-success type="submit" wire:loading.attr="disabled" class="col-md-3 offset-md-5">
                    {{ __('Save') }}
                </x-button-success>
            </div>
        </form>
    </x-slot>

    <x-slot name="footer">
        <x-button-danger wire:click="$toggle('confirmingRole')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-dang-button>
    </x-slot>
</x-dialog-modal>