<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MainSiteData;

$path = MainSiteData::getValue('site_logo');
echo "DB Path: $path\n";
echo "Resolved URL: " . site_image($path) . "\n";

$icon = MainSiteData::getValue('site_icon');
echo "DB Icon: $icon\n";
echo "Resolved Icon URL: " . site_image($icon) . "\n";
