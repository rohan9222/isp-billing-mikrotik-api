<ul class="navbar-nav" data-top-nav-dropdowns="data-top-nav-dropdowns">
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('dashboard.index') }}" role="button">Dashboard</a>
    </li>
    <li class="nav-item">
        <!-- parent pages-->
        <a wire:navigate.hover wire:current="active" class="nav-link" href="{{route('mikrotik-sync') }}" role="button">Mikrotik Sync</a>
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
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="collections">Collection</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="collections">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('payment-collection') }}">Payment Collection</a>
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('collection-edit') }}">
                    Collection Edit
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
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="admin">Admin</a>
        <div class="dropdown-menu dropdown-caret dropdown-menu-card border-0 mt-0" aria-labelledby="admin">
            <div class="bg-white dark__bg-1000 rounded-3 py-2">
                <img class="img-dropdown" src="{{asset('images/authentication-corner.png')}}" width="60" alt="" />
                <a wire:navigate.hover wire:current="active" class="dropdown-item link-600 fw-medium" href="{{route('sms-setup') }}">
                    SMS Setup
                </a>
            </div>
        </div>
    </li>
</ul>
