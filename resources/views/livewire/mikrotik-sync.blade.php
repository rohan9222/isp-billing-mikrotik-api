<div x-data="{ isOpen: false }" class="zoom-in">
    <!-- Header -->
    <x-slot name="header">
        {{ __('Mikrotik List') }}
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="col-lg-8 col-md-11 col-sm-11">
            <div class="row">
                <div class="col">
                    <div class="p-1">
                        <!-- Toggle Button -->
                        <button
                            @click="isOpen = !isOpen; if (!isOpen) { $wire.set('router_name', ''); $wire.set('ip_address', ''); $wire.set('username', ''); $wire.set('password', ''); $wire.set('ssh_port', ''); $wire.set('api_port', ''); }"
                            class="btn btn-sm btn-primary"
                            type="button">
                            <span x-text="isOpen ? 'Hide This' : 'Add Mikrotik'"></span>
                        </button>
                    </div>
                    <!-- Collapse Section -->
                    <div x-show="isOpen" x-transition x-cloak>
                        <div class="card card-body">
                            <form wire:submit.prevent="submit">
                                <div class="row form-group input-group-sm">
                                    <div class="col">
                                        <input class="form-control" type="text" id="router_name" wire:model="router_name" placeholder="Router Name" aria-label="Router Name" name="router_name" >
                                        <x-input-error for='router_name' />
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="text" id="ip_address" wire:model="ip_address" placeholder="IP Address" aria-label="IP Address">
                                        <x-input-error for='ip_address' />
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="text" id="username" wire:model="username" placeholder="Username" aria-label="Username">
                                        <x-input-error for='username' />
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="password" id="password" wire:model="password" placeholder="Password" aria-label="Password">
                                        <x-input-error for='password' />
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="number" id="ssh_port" wire:model="ssh_port" placeholder="SSH Port" aria-label="SSH Port">
                                        <x-input-error for='ssh_port' />
                                    </div>
                                    <div class="col">
                                        <input class="form-control" type="number" id="api_port" wire:model="api_port" placeholder="API Port" aria-label="API Port">
                                        <x-input-error for='api_port' />
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-2" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card row mt-3 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Router Name</th>
                            <th>IP Address</th>
                            <th>Username</th>
                            <th>SSH Port</th>
                            <th>API Port</th>
                            <th>Customer</th>
                            <th>Action</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routers as $router)
                            <tr class="text-center">
                                <td>{{ $router->router_name }}</td>
                                <td>{{ $router->ip_address }}</td>
                                <td>{{ $router->username }}</td>
                                <td>{{ $router->ssh_port ?? '-' }}</td>
                                <td>{{ $router->api_port ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('customers.index') }}" id="customers-{{ $router->id }}" class="btn btn-sm btn-success customers">{{ $router->user_list_count }}</a>
                                </td>
                                <td>
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="action-{{ $router->id }}" class="toggle-checkbox"
                                            wire:click="connect_toggle({{ $router->id }})" {{ $router->action === 'connected' ? 'checked' : '' }}
                                            x-data="{
                                                dataSyncFunction(customer_id) {
                                                    $(customer_id).text('');
                                                    let spinnerSpan = document.createElement('span');
                                                    spinnerSpan.classList.add('spinner-border', 'spinner-border-sm');
                                                    spinnerSpan.setAttribute('aria-hidden', 'true');
                                                    $(customer_id).append(spinnerSpan);
                                                }
                                            }"
                                            x-on:click="if($event.target.checked){dataSyncFunction('#customers-' + {{ $router->id }})}">
                                        <label for="action-{{ $router->id }}" class="toggle-label">
                                            <span class="connected-text">Connected</span>
                                            <span class="disconnected-text">Disconnected</span>
                                            <span class="toggle-switch"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $router->id }})"><i class="bi bi-trash"></i></button>
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $router->id }})" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                    <button
                                        x-data="{
                                            dataSyncFunction(customer_id) {
                                                $(customer_id).text('');
                                                let spinnerSpan = document.createElement('span');
                                                spinnerSpan.classList.add('spinner-border', 'spinner-border-sm');
                                                spinnerSpan.setAttribute('aria-hidden', 'true');
                                                $(customer_id).append(spinnerSpan);
                                            }
                                        }"
                                        x-on:click="dataSyncFunction('#customers-' + {{ $router->id }})"
                                        class="btn btn-sm btn-primary" wire:click="dataSync({{ $router->id }})"><i class="bi bi-arrow-repeat"></i></button>
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

    <!-- Synchronize Data (to show spinner) -->
    <div
        x-data="{
            dataSyncFunction() {
                $('.customers').text('');
                let spinnerSpan = document.createElement('span');
                spinnerSpan.classList.add('spinner-border', 'spinner-border-sm');
                spinnerSpan.setAttribute('aria-hidden', 'true');
                $('.customers').append(spinnerSpan);
            }
        }" x-init="$wire.allSync() && dataSyncFunction()">
    </div>
</div>
