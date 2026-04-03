<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\PortalPanelProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\JetstreamServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    PortalPanelProvider::class,
    FortifyServiceProvider::class,
    JetstreamServiceProvider::class,
    TelescopeServiceProvider::class,
];
