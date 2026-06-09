@if(auth()->user()->hasRole('Reseller'))
<ul class="navbar-nav" data-top-nav-dropdowns="data-top-nav-dropdowns">
    <li class="nav-item">
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('reseller.dashboard') }}" role="button">Dashboard</a>
    </li>
    
    @canany(['view-customer', 'create-customer', 'edit-customer', 'delete-customer'])
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="resellerCustomers">Customers</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="resellerCustomers">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('reseller.customers.index') }}">Customer List</a>
                @can('create-customer')
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('reseller.customers.create') }}">New Customer</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany

    @canany(['payment-collection', 'payment-collection-edit', 'payment-collection-invoice', 'payment-history', 'payment-collection-report', 'collection-list', 'without-collection-list', 'amount-collection'])
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="resellerBilling">Billing</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="resellerBilling">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                @can('payment-collection')
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('payment-collection') }}">Payment Collection</a>
                @endcan
                @can('payment-collection-edit')
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('collection-edit') }}">Collection Edit</a>
                @endcan
                @can('payment-collection-invoice')
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('payment-invoice') }}">Payment Invoice</a>
                @endcan
                @canany(['payment-collection-report', 'collection-list', 'without-collection-list', 'amount-collection'])
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('collection-report.index') }}">Collection Report</a>
                @endcanany
            </div>
        </div>
    </li>
    @endcanany

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="resellerAccount">My Account</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="resellerAccount">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('reseller.wallet.index') }}">Wallet & Earnings</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('reseller.vouchers.index') }}">Vouchers</a>
            </div>
        </div>
    </li>

    @can('package-setup')
    <li class="nav-item">
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('reseller.packages.index') }}" role="button">Packages</a>
    </li>
    @endcan
</ul>
@else
<ul class="navbar-nav" data-top-nav-dropdowns="data-top-nav-dropdowns">
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('dashboard') }}" role="button">Dashboard</a>
    </li>
    <li class="nav-item">
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-sync') }}" role="button">Mikrotik Sync</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="mikrotikSetupNav">Mikrotik Setup</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="mikrotikSetupNav">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-ip-setup') }}">IP & Pool</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-pppoe-setup') }}">PPPoE Server</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-radius-setup') }}">RADIUS</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-firewall-setup') }}">Firewall</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-walled-garden') }}">
                    Walled Garden <span class="badge rounded-pill ms-2 badge-subtle-info">New</span>
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-queue-setup') }}">Queues</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-vpn-setup') }}">VPN Server</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-interface-setup') }}">Interfaces & VLAN</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-traffic-monitor') }}">Live Traffic</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-log-viewer') }}">Router Logs</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-backup-setup') }}">Backup & Restore</a>

                <hr class="my-2">
                {{-- Highlighted Hotspot --}}
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('mikrotik-hotspot-manager') }}">
                    <span>📶 Hotspot Manager</span>
                </a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('address-setup') }}" role="button">Address</a>
    </li>
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('package-list-setup') }}" role="button">Package</a>
    </li>
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('new-customer') }}" role="button">Create Customer</a>
    </li>
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('customers.index') }}" role="button">Customers</a>
    </li>
    <li class="nav-item">
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('admin-tickets') }}" role="button">Support Tickets</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="collections">Collection</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="collections">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('payment-collection') }}">Payment Collection</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('collection-edit') }}">
                    Collection Edit
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('payment-invoice') }}">
                    Payment Invoice
                </a>
            </div>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="reports">Reports</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="reports">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('collection-report.index') }}">
                    Collections Report
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('customer-summary') }}">
                    Customer Summary
                </a>
                <a wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('dis-report') }}">
                    DIS Summary
                    <span class="badge rounded-pill ms-2 badge-subtle-success">New</span>
                </a>
            </div>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="financeDropdown">Finance</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="financeDropdown">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('admin.expenses') }}">
                    Expense Management
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{ route('admin.profit-summary') }}">
                    Profit & Loss
                </a>
            </div>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="admin">Admin</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="admin">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('site-settings') }}">
                    Master Setup
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('admin.resellers.index') }}">
                    Reseller Setup
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('admin.activity-logs') }}">
                    Activity Logs
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('sms-setup') }}">
                    SMS Setup
                </a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('sms-bridge.index') }}">
                    SMS Bridge
                </a>
            </div>
        </div>
    </li>
</ul>
@endif
