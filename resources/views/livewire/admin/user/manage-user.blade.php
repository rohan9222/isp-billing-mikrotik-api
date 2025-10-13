<div>
    <x-slot name="header">
        {{ __('Manage Users') }}
    </x-slot>

    <div class="card">
        <div class="card-body row">
            <div class="col-12">
                @can('create-user')
                    <button wire:click="newUser" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> {{ __('Add New User') }}</button>
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

                    <input type="text" class="form-control form-control-sm w-250" wire:model.live="search" placeholder="Search" aria-label="Search">
                </div>
            </div>

            <div class="col-12 table-responsive">
                <table class="table table-striped table-hover table-bordered border-success">
                    <thead>
                        <tr>
                            <th class="table-success border border-success" scope="col">S#</th>
                            <th class="table-success border border-success" scope="col">Name</th>
                            <th class="table-success border border-success" scope="col">Mobile</th>
                            <th class="table-success border border-success" scope="col">Email</th>
                            <th class="table-success border border-success" scope="col">Address</th>
                            <th class="table-success border border-success" scope="col">Roles</th>
                            <th class="table-success border border-success" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->address }}</td>
                            <td>
                                @foreach ($user->getRoleNames() as $role)
                                    <span class="badge bg-primary">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                    @if (Auth::user()->hasRole('Super Admin'))
                                        <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</button>
                                    @endif
                                    @if (Auth::user()->hasRole('Super Admin') && Auth::user()->id == $user->id)
                                        <button type="submit" class="btn btn-danger btn-sm" wire:click="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"><i class="bi bi-trash"></i> Delete</button>
                                    @endif
                                @else
                                    @can('edit-user')
                                        <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</button>
                                    @endcan

                                    @can('delete-user')
                                        @if (Auth::user()->id != $user->id)
                                            <button type="submit" class="btn btn-danger btn-sm" wire:click="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"><i class="bi bi-trash"></i> Delete</button>
                                        @endif
                                    @endcan
                                @endif
                            </td>
                        </tr>
                        @empty
                            <td colspan="5">
                                <span class="text-danger">
                                    <strong>No User Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>

    @include('livewire.admin.user.user-form')
</div>