<?php

use App\Http\Controllers\CollectionReportController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MainSiteController;
use App\Livewire\AddressSetup;
use App\Livewire\Admin\ManageRole;
use App\Livewire\Admin\ManageUser;
use App\Livewire\CollectionEdit;
use App\Livewire\CommentSubmit;
use App\Livewire\CustomerList;
use App\Livewire\CustomerSummary;
use App\Livewire\MainSiteSetup;
use App\Livewire\Mikrotik\BackupManager;
use App\Livewire\Mikrotik\FirewallSetup;
use App\Livewire\Mikrotik\HotspotManager;
use App\Livewire\Mikrotik\InterfaceSetup;
use App\Livewire\Mikrotik\IpSetup;
use App\Livewire\Mikrotik\PppoeSetup;
use App\Livewire\Mikrotik\QueueSetup;
use App\Livewire\Mikrotik\RadiusSetup;
use App\Livewire\Mikrotik\RouterLogViewer;
use App\Livewire\Mikrotik\TrafficMonitor;
use App\Livewire\Mikrotik\VpnSetup;
use App\Livewire\MikrotikSync;
use App\Livewire\NewCustomer;
use App\Livewire\NotificationListAll;
use App\Livewire\PackageListSetup;
use App\Livewire\Payment\Invoice;
use App\Livewire\PaymentCollection;
use App\Livewire\Report\DisReport;
use App\Livewire\SMSSetup;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Extract domain host from APP_URL for consistent subdomain routing
$baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url');

// Main domain
Route::domain($baseDomain)->group(function () {
    Route::get('/', [MainSiteController::class, 'index'])->name('welcome');
    Route::get('/all-packages', [MainSiteController::class, 'allPackages'])->name('all-packages');

    Route::get('/portal', function () {
        $host = request()->getHost();
        if (Str::startsWith($host, 'portal.')) {
            return redirect('/');
        }

        return redirect()->away('https://portal.'.$host);
    });

    Route::get('/billing', function () {
        $host = request()->getHost();
        if (Str::startsWith($host, 'billing.')) {
            return redirect('/');
        }

        return redirect()->away('https://billing.'.$host);
    });

    Route::get('/php-artisan-optimize', function () {
        $commands = [
            'config:cache',
            'route:cache',
            'view:cache',
            'cache:clear',
            'event:cache',
            'compiled:clear',
            'storage:link',
            // Add more commands as needed
        ];

        $output = [];
        foreach ($commands as $command) {
            try {
                Artisan::call($command);
                $output[$command] = Artisan::output();
            } catch (Exception $e) {
                $output[$command] = $e->getMessage();
            }
        }

        return response()->json($output);
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () use ($baseDomain) {
    Route::domain('billing.'.$baseDomain)->group(function () {
        Route::get('/system/db-backup/download/{filename}', function ($filename) {
            if (str_contains($filename, '/') || str_contains($filename, '\\')) {
                abort(403, 'Invalid filename.');
            }
            $path = base_path('backups/'.$filename);
            if (file_exists($path)) {
                return response()->download($path);
            }
            abort(404, 'Backup file not found.');
        })->name('system.db-backup.download');

        Route::redirect('/', '/dashboard');

        Route::post('customers/enable/{id}', [CustomersController::class, 'customerEnable'])->name('customers.enable');

        Route::resources([
            'dashboard' => DashboardController::class,
            'customers' => CustomersController::class,
            'collection-report' => CollectionReportController::class,
        ]);

        Route::get('/new/customers', CustomerList::class)->name('customers-new');
        Route::get('/admin-users', ManageUser::class)->name('admin-users');
        Route::get('/admin-roles', ManageRole::class)->name('admin-roles');
        Route::get('/mikrotik', MikrotikSync::class)->name('mikrotik-sync');

        // Mikrotik Setup Routes
        Route::prefix('mikrotik-setup')->group(function () {
            Route::get('/ip', IpSetup::class)->name('mikrotik-ip-setup');
            Route::get('/pppoe', PppoeSetup::class)->name('mikrotik-pppoe-setup');
            Route::get('/queue', QueueSetup::class)->name('mikrotik-queue-setup');
            Route::get('/firewall', FirewallSetup::class)->name('mikrotik-firewall-setup');
            Route::get('/hotspot', HotspotManager::class)->name('mikrotik-hotspot-setup'); // merged → HotspotManager
            Route::get('/hotspot-manager', HotspotManager::class)->name('mikrotik-hotspot-manager');
            Route::get('/radius', RadiusSetup::class)->name('mikrotik-radius-setup');
            Route::get('/vpn', VpnSetup::class)->name('mikrotik-vpn-setup');
            Route::get('/interface', InterfaceSetup::class)->name('mikrotik-interface-setup');
            Route::get('/traffic', TrafficMonitor::class)->name('mikrotik-traffic-monitor');
            Route::get('/logs', RouterLogViewer::class)->name('mikrotik-log-viewer');
            Route::get('/backup', BackupManager::class)->name('mikrotik-backup-setup');
        });

        Route::get('/address', AddressSetup::class)->name('address-setup');
        Route::get('/packages', PackageListSetup::class)->name('package-list-setup');
        Route::get('/sms', SMSSetup::class)->name('sms-setup');
        Route::get('/create-customer', NewCustomer::class)->name('new-customer');

        // payment routes
        Route::get('/payment-collection', PaymentCollection::class)->name('payment-collection');
        Route::get('/payment-collection-edit', CollectionEdit::class)->name('collection-edit');
        Route::get('/payment-invoice', Invoice::class)->name('payment-invoice');

        // all report
        Route::get('/customer-summary', CustomerSummary::class)->name('customer-summary');
        Route::get('/report/dis-report-table', [DisReport::class, 'getData'])->name('dis-report-table');
        Route::get('/report/dis-report', DisReport::class)->name('dis-report');

        // site settings (consolidated)
        Route::get('/site-settings', MainSiteSetup::class)
            ->middleware(DispatchServingFilamentEvent::class)
            ->name('site-settings');

        // main site content management (deprecate name but keeps route if needed or redirection)
        Route::get('/main-site-setup', function () {
            return redirect()->route('site-settings');
        });

        Route::get('/all-notifications', NotificationListAll::class)->name('notifications');
        // Route::get('/edit-customer', EditCustomer::class);
        // Route::get('/customers', CustomerList::class);

        Route::get('import-form', [ImportController::class, 'importForm'])->name('import.form');
        Route::post('collection-form', [ImportController::class, 'collectionForm'])->name('collection.form');
        Route::post('monthly-bill-form', [ImportController::class, 'monthlyBillForm'])->name('monthly.bill.form');
        // Route::post('import-preview', [ImportController::class, 'importView'])->name('import.preview');
        Route::post('import-store', [ImportController::class, 'import'])->name('import');

        // Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
        // Route::post('/user/profile/upload', [UserProfileController::class, 'uploadFile'])->name('user.profile.upload');
        // Route::get('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
        // Route::get('/user/password/update', [UserProfileController::class, 'updatePassword'])->name('user.password.update');

        Route::get('/submit-comment', CommentSubmit::class)->name('submit.comment');
    });

    // Route::get('/schedulesTask', [ScheduledTasksController::class, 'userDisable']);
    // Route::get('/backup-database', [ScheduledTasksController::class, 'backupDatabase']);

    // Route::get('/bkash', [BkashController::class, 'index']);
    // Route::post('/bkash/payment', [BkashController::class, 'createPayment']);
    // Route::post('/bkash/execute/{paymentID}', [BkashController::class, 'executePayment']);

    Route::domain('portal.'.$baseDomain)->group(function () {});
});
