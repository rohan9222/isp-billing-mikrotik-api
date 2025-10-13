<div class="col">
    <div class="d-flex justify-content-center">
        <div class="col-8">
            <h4 class="text-center">Mikrotik List</h4>
            <div class="row">
                <div class="col">
                    <div x-data="{ text: 'Add Mikrotik' }">
                        <button @click="text = text === 'Add Mikrotik' ? 'Hide This' : 'Add Mikrotik'" class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <span x-text="text"></span>
                        </button>
                    </div>

                    <div class="" id="collapseExample">
                        <div class="card card-body">
                            <form wire:submit.prevent="submit">
                                <div class="row">
                                    <div class="col"><input class="form-control" type="text" id="router_name" wire:model="router_name">@error('router_name') <span class="text-danger">{{ $message }}</span> @enderror</div>
                                    <div class="col"><input class="form-control" type="text" id="ip_address" wire:model="ip_address">@error('ip_address') <span class="text-danger">{{ $message }}</span> @enderror</div>
                                    <div class="col"><input class="form-control" type="text" id="username" wire:model="username">@error('username') <span class="text-danger">{{ $message }}</span> @enderror</div>
                                    <div class="col"><input class="form-control" type="password" id="password" wire:model="password">@error('password') <span class="text-danger">{{ $message }}</span> @enderror</div>
                                    <div class="col"><input class="form-control" type="number" id="ssh_port" wire:model="ssh_port">@error('ssh_port') <span class="text-danger">{{ $message }}</span> @enderror</div>
                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Router Name</th>
                            <th>IP Address</th>
                            <th>Username</th>
                            <th>SSH Port</th>
                            <th>Customer</th>
                            <th>Action</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routers as $router)
                            <tr>
                                <td>{{ $router->router_name }}</td>
                                <td>{{ $router->ip_address }}</td>
                                <td>{{ $router->username }}</td>
                                <td>{{ $router->ssh_port }}</td>
                                <td><a href="{{ route('customers.index') }}" id="customers-{{ $router->id }}" class="btn btn-sm btn-success customers">{{ $router->user_list_count }}</a></td>
                                <td>
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="action-{{ $router->id }}" class="toggle-checkbox" wire:click="connect_toggle({{ $router->id }})" {{ $router->action == 'connected' ? 'checked' : '' }}>
                                        <label for="action-{{ $router->id }}" class="toggle-label">
                                            <span class="connected-text">Connected</span>
                                            <span class="disconnected-text">Disconnected</span>
                                            <span class="toggle-switch"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $router->id }})"><i class="bi bi-trash"></i></button>
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $router->id }})"><i class="bi bi-pencil-square"></i></button>
                                    <button
                                        x-data="{
                                            dataSyncFunction(customer_id) {
                                                $(customer_id).text('');
                                                spinnerSpan = document.createElement('span');
                                                spinnerSpan.setAttribute('class', 'spinner-border spinner-border-sm');
                                                spinnerSpan.setAttribute('aria-hidden', 'true');
                                                $(customer_id).append(spinnerSpan);
                                            }
                                        }"
                                        @click="dataSyncFunction('#customers-' + {{ $router->id }})"
                                        class="btn btn-sm btn-primary" wire:click="dataSync({{ $router->id }})"><i class="bi bi-arrow-repeat"></i></button>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $routers->links() }}
            </div>
        </div>
    </div>

    <div
        x-data="{
            dataSyncFunction() {
                $('.customers').text('');
                spinnerSpan = document.createElement('span');
                spinnerSpan.setAttribute('class', 'spinner-border spinner-border-sm');
                spinnerSpan.setAttribute('aria-hidden', 'true');
                $('.customers').append(spinnerSpan);
            }
        }" x-init="$wire.allSync() && dataSyncFunction()" >
    </div>
</div>
