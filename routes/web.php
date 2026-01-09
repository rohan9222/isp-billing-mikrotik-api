<?php

use App\Http\Controllers\CollectionReportController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MikrotikController;
use App\Livewire\AddressSetup;
use App\Livewire\Admin\ManageRole;
use App\Livewire\Admin\ManageUser;
use App\Livewire\CollectionEdit;
use App\Livewire\CustomerList;
use App\Livewire\CustomerSummary;
use App\Livewire\EditCustomer;
use App\Livewire\MikrotikSync;
use App\Livewire\NewCustomer;
use App\Livewire\NotificationListAll;
use App\Livewire\PackageListSetup;
use App\Livewire\PaymentCollection;
use App\Livewire\SMSSetup;
use App\Livewire\SiteSettings;
use App\Livewire\Report\DisReport;
use App\Livewire\Payment\Invoice;
use Illuminate\Support\Facades\Route;

// Main domain
Route::domain(config('app.url'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        dd('pricing');
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
            } catch (\Exception $e) {
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
])->group(function () {
    Route::domain('billing.' . config('app.url'))->group(function () {
        Route::redirect('/', '/login');

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
        Route::get('/address', AddressSetup::class)->name('address-setup');
        Route::get('/packages', PackageListSetup::class)->name('package-list-setup');
        Route::get('/sms', SMSSetup::class)->name('sms-setup');
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

        // site settings
        Route::get('/site-settings', SiteSettings::class)->name('site-settings');

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
    });

    // Route::get('/schedulesTask', [ScheduledTasksController::class, 'userDisable']);
    // Route::get('/backup-database', [ScheduledTasksController::class, 'backupDatabase']);

    // Route::get('/bkash', [BkashController::class, 'index']);
    // Route::post('/bkash/payment', [BkashController::class, 'createPayment']);
    // Route::post('/bkash/execute/{paymentID}', [BkashController::class, 'executePayment']);

    Route::domain('portal.' . config('app.url'))->group(function () {
        Route::redirect('/', '/login');
    });
});
