<nav id="navbar" class="navigation d-lg-none fixed-bottom bg-body-tertiary shadow-sm z-1">
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link" wire:navigate.hover wire:current="active" href="{{ route('dashboard.index') }}">
                <span class="icon">
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="text">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" wire:navigate.hover wire:current="active" href="{{ route('payment-collection') }}">
                <span class="icon">
                    <i class="bi bi-cash-coin"></i>
                </span>
                <span class="text">Collection</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" wire:navigate.hover wire:current="active" href="{{ route('customers.index') }}">
                <span class="icon">
                    <i class="bi bi-people-fill"></i>
                </span>
                <span class="text">Customers</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" wire:navigate.hover wire:current="active" href="{{ route('new-customer') }}">
                <span class="icon">
                    <i class="bi bi-person-fill-add"></i>
                </span>
                <span class="text">New Customer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" wire:navigate.hover wire:current="active" href="{{ route('site-settings') }}">
                <span class="icon">
                    <i class="bi bi-gear"></i>
                </span>
                <span class="text">Settings</span>
            </a>
        </li>
    </ul>
</nav>
