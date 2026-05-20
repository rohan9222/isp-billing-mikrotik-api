<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MainSiteData;

$settings = [
    'site_logo' => MainSiteData::getValue('site_logo'),
    'site_icon' => MainSiteData::getValue('site_icon'),
    'site_favicon' => MainSiteData::getValue('site_favicon'),
];

print_r($settings);
