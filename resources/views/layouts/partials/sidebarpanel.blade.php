<ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">Dashboard</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider" />
            </div>
        </div>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('dashboard.index') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-speedometer2 me-2"></i>
                </span>
                <span class="nav-link-text ps-1">Dashboard</span>
            </div>
        </a>
    </li>
    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">Mikrotik</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider" />
            </div>
        </div>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('mikrotik-sync') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-router-fill"></i>
                </span>
                <span class="nav-link-text ps-1">Mikrotik Sync</span>
            </div>
        </a>

        <!-- Mikrotik Setup Dropdown -->
        <a class="nav-link dropdown-indicator collapsed" href="#mikrotikSetup" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="mikrotikSetup">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-tools"></i>
                </span>
                <span class="nav-link-text ps-1">Mikrotik Setup</span>
            </div>
        </a>
        <ul class="nav collapse" id="mikrotikSetup" style="">
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-ip-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">IP & Pool</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-pppoe-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">PPPoE Server</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-hotspot-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Hotspot</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-radius-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">RADIUS</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-firewall-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Firewall</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-queue-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Queues</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-vpn-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">VPN Server</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-interface-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Interfaces & VLAN</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-traffic-monitor') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Live Traffic</span><span class="badge rounded-pill ms-2 badge-subtle-success">New</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-log-viewer') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Router Logs</span><span class="badge rounded-pill ms-2 badge-subtle-warning">Log</span></div>
            </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-backup-setup') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Backup & Restore</span><span class="badge rounded-pill ms-2 badge-subtle-primary">Admin</span></div>
            </a></li>
        </ul>
    </li>
    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">Customers</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider" />
            </div>
        </div>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('new-customer') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-person-fill-add"></i>
                </span>
                <span class="nav-link-text ps-1">New Customer</span>
            </div>
        </a>
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('customers.index') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-people-fill"></i>
                </span>
                <span class="nav-link-text ps-1">Customers</span>
            </div>
        </a>
        <!-- parent pages-->
        <a class="nav-link dropdown-indicator collapsed" href="#collections" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collections">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-cash-coin"></i>
                </span>
                <span class="nav-link-text ps-1">Collection</span>
            </div>
        </a>
        <ul class="nav collapse" id="collections" style="">
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('payment-collection') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Payment Collection</span></div>
                </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('collection-edit') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Collection Edit</span></div>
                </a>
            </li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('payment-invoice') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Payment Invoice</span></div>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">Reports</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider" />
            </div>
        </div>
        <!-- parent pages-->
        <a class="nav-link dropdown-indicator collapsed" href="#reports" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="reports">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-journal-text"></i>
                </span>
                <span class="nav-link-text ps-1">Reports</span>
            </div>
        </a>
        <ul class="nav collapse" id="reports" style="">
            <li class="nav-item"><a wire:current="active" class="nav-link" href="{{route('collection-report.index') }}">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Collections Report</span></div>
                </a></li>
            <li class="nav-item"><a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('customer-summary') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Customer Summary</span></div>
                </a>
            </li>
            <li class="nav-item"><a wire:current="active" class="nav-link" href="{{route('dis-report') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">DIS Summary</span><span class="badge rounded-pill ms-2 badge-subtle-success">New</span></div>
                </a>
            </li>
        </ul>
    </li>
    </li>
    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">Admin</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider" />
            </div>
        </div>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('address-setup') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-buildings"></i>
                </span>
                <span class="nav-link-text ps-1">Address Setup</span>
            </div>
        </a>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('site-settings') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-gear-fill"></i>
                </span>
                <span class="nav-link-text ps-1">Master Setup</span>
            </div>
        </a>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{ route('package-list-setup') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-box2"></i>
                </span>
                <span class="nav-link-text ps-1">Package Setup</span>
            </div>
        </a>
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('sms-setup') }}" role="button">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon">
                    <i class="bi bi-envelope-check"></i>
                </span>
                <span class="nav-link-text ps-1">SMS Setup</span>
            </div>
        </a>
    </li>
</ul>
