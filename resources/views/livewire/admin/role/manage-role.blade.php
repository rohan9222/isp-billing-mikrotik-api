<div>
    <x-slot name="header">
        {{ __('Manage Roles') }}
    </x-slot>

    <div class="card">
        <div class="card-body row">
            <div class="col-12">
                @can('create-role')
                    <x-button-success wire:click="newRole" wire:loading.attr="disabled">
                        <i class="bi bi-plus-circle"></i>
                        {{ __('Add New Role') }}
                    </x-button-success>
                @endcan
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between p-2">
                    <select class="form-select form-select-sm w-90" wire:model.live="perPage" aria-label="Default select example">
                        <option >Select One</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="*">All</option>
                    </select>
                </div>
            </div>

            <div class="col-12 table-responsive">
                <table class="table table-striped table-hover table-bordered border-success">
                    <thead>
                        <tr>
                            <th class="table-success border border-success" scope="col">S#</th>
                            <th class="table-success border border-success" scope="col">Name</th>
                            <th class="table-success border border-success" scope="col">Permissions</th>
                            <th class="table-success border border-success" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <th class="border border-success" scope="row">{{ $loop->iteration }}</th>
                                <td class="border border-success">{{ $role->name }}</td>
                                <td class="border border-success">
                                    @forelse ($role->permissions as $permission)
                                        <span class="badge bg-primary">{{ $permission->name }}</span>
                                    @empty
                                        @if ($role->name == 'Super Admin')
                                            <span class="badge bg-primary">All</span>
                                        @else
                                            <span class="badge bg-secondary">No Permissions</span>
                                        @endif
                                    @endforelse
                                </td>
                                <td class="border border-success">
                                    @can('edit-role')
                                        <button class="btn btn-primary btn-sm" wire:click="editRole({{ $role->id }})"><i class="bi bi-pencil-square"></i> Edit</button>
                                    @endcan
                                    @can('delete-role')
                                        <button class="btn btn-danger btn-sm" wire:click="deleteRole({{ $role->id }}, '{{ $role->name }}')"><i class="bi bi-trash"></i> Delete</button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <td colspan="5">
                                <span class="text-danger">
                                    <strong>No Role Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $roles->links() }}
        </div>
    </div>

    @include('livewire.admin.role.role-form')
</div>