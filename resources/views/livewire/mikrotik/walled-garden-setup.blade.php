<div class="row">
    <div class="col-12 mt-4">
        <div class="card md:min-h-[82vh]">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Walled Garden / Permitted URLs') }}</h6>
                <p class="text-sm text-muted mb-0">Manage URLs or IPs (like bKash, IPay, Nagad) that users can access even when expired.</p>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="selectedRouter" class="form-control-label">{{ __('Select Router') }}</label>
                            <select class="form-control" id="selectedRouter" wire:model.live="selectedRouter">
                                <option value="">Select a Router</option>
                                @foreach($routers as $router)
                                    <option value="{{ $router->id }}">{{ $router->router_name }} ({{ $router->ip_address }})</option>
                                @endforeach
                            </select>
                            @error('selectedRouter') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="form-control-label">{{ __('Type') }}</label>
                            <select class="form-control" id="type" wire:model="type">
                                <option value="url">URL (Domain Name)</option>
                                <option value="ip">IP Address</option>
                            </select>
                            @error('type') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="url_or_ip" class="form-control-label">{{ __('URL or IP Address') }}</label>
                            <input type="text" class="form-control" id="url_or_ip" wire:model="url_or_ip" placeholder="e.g. bkash.com or 192.168.1.10">
                            @error('url_or_ip') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="comment" class="form-control-label">{{ __('Comment (Optional)') }}</label>
                            <input type="text" class="form-control" id="comment" wire:model="comment" placeholder="e.g. bKash payment gateway">
                            @error('comment') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="button" class="btn btn-primary w-100" wire:click="addPermitted" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="addPermitted">{{ __('Add and Sync to Router') }}</span>
                            <span wire:loading wire:target="addPermitted">{{ __('Syncing...') }}</span>
                        </button>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Type') }}</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('URL / IP') }}</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Comment') }}</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($urls as $url)
                                    <tr>
                                        <td>
                                            <span class="badge {{ $url->type == 'url' ? 'bg-gradient-info' : 'bg-gradient-success' }}">{{ strtoupper($url->type) }}</span>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $url->url_or_ip }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm text-secondary mb-0">{{ $url->comment }}</p>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-link text-danger text-gradient px-3 mb-0" 
                                                    wire:click="deletePermitted({{ $url->id }})" 
                                                    wire:confirm="{{ __('Are you sure you want to remove this from Mikrotik?') }}">
                                                <i class="far fa-trash-alt me-2"></i>Remove
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-sm text-secondary mb-0">{{ __('No permitted URLs or IPs added yet.') }}</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('One-Click Router Redirection Setup') }}</h6>
                <p class="text-sm text-muted mb-0">Automatically configure PPP profiles, Hotspot profiles, and NAT rules for the selected router.</p>
            </div>
            <div class="card-body pt-4 p-3">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label for="portal_ip" class="form-control-label">{{ __('Portal IP Address (Local IP of this server)') }}</label>
                            <input type="text" class="form-control" id="portal_ip" wire:model="portal_ip" placeholder="e.g. 192.168.88.10">
                            @error('portal_ip') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label for="expired_speed" class="form-control-label">{{ __('Expired User Speed Limit') }}</label>
                            <input type="text" class="form-control" id="expired_speed" wire:model="expired_speed" placeholder="e.g. 128k/128k">
                            @error('expired_speed') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <button type="button" class="btn bg-gradient-dark mb-0 w-100" 
                                wire:click="runRouterSetup" 
                                wire:loading.attr="disabled"
                                wire:confirm="This will create/update the 'Expired' profile and NAT rules on the router. Continue?">
                            <i class="bi bi-gear-wide-connected me-2"></i>
                            <span wire:loading.remove wire:target="runRouterSetup">{{ __('Run Automated Setup on Router') }}</span>
                            <span wire:loading wire:target="runRouterSetup">{{ __('Configuring Router...') }}</span>
                        </button>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-gray-100 border-radius-lg">
                    <h6 class="text-sm font-weight-bold"><i class="bi bi-info-circle me-1 text-info"></i> What this setup does:</h6>
                    <ul class="text-xs mb-0 ps-3">
                        <li>Creates/Updates a PPP Profile named <strong>Expired</strong> with speed <strong>{{ $expired_speed }}</strong>.</li>
                        <li>Creates/Updates a Hotspot User Profile named <strong>Expired</strong> (if available).</li>
                        <li>Adds users in these profiles to the <strong>EXPIRED_USERS</strong> firewall list.</li>
                        <li>Configures a NAT rule to redirect all traffic from <strong>EXPIRED_USERS</strong> to <strong>{{ $portal_ip ?: '[Set IP above]' }}</strong>.</li>
                        <li>Automatically bypasses the <strong>Permitted URLs</strong> (bKash, etc.) defined above.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
