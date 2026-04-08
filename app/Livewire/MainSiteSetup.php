<?php

namespace App\Livewire;

use App\Http\Controllers\MikrotikController;
use App\Models\MainSiteData;
use App\Models\RouterList;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;

class MainSiteSetup extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        if (! hasAccess(['Super Admin'], ['site-setup'])) {
            abort(403, 'Unauthorized action.');
        }

        $this->form->fill([
            // Identity & Status
            'site_name' => MainSiteData::getValue('site_name', config('app.name')),
            'site_title' => MainSiteData::getValue('site_title'),
            'site_status' => MainSiteData::getValue('site_status', 'active'),
            'site_maintenance' => (bool) MainSiteData::getValue('site_maintenance', false),
            'site_message' => MainSiteData::getValue('site_message'),

            // Assets
            'site_logo' => MainSiteData::getValue('site_logo'),
            'site_icon' => MainSiteData::getValue('site_icon'),
            'site_favicon' => MainSiteData::getValue('site_favicon'),

            // SEO
            'site_description' => MainSiteData::getValue('site_description'),
            'site_keywords' => MainSiteData::getValue('site_keywords'),
            'site_author' => MainSiteData::getValue('site_author'),

            // Contact
            'site_email' => MainSiteData::getValue('site_email'),
            'site_phone' => MainSiteData::getValue('site_phone', '01700000000'),
            'site_address' => MainSiteData::getValue('site_address'),
            'site_map' => MainSiteData::getValue('site_map'),

            // Socials (SiteSetting mapping)
            'site_facebook' => MainSiteData::getValue('site_facebook'),
            'site_twitter' => MainSiteData::getValue('site_twitter'),
            'site_instagram' => MainSiteData::getValue('site_instagram'),
            'site_linkedin' => MainSiteData::getValue('site_linkedin'),
            'site_pinterest' => MainSiteData::getValue('site_pinterest'),
            'site_youtube' => MainSiteData::getValue('site_youtube'),
            'site_whatsapp' => MainSiteData::getValue('site_whatsapp'),

            // Billing & Invoicing
            'site_currency' => MainSiteData::getValue('site_currency', 'BDT'),
            'site_invoice_prefix' => MainSiteData::getValue('site_invoice_prefix', 'INV-'),
            'site_invoice_logo' => MainSiteData::getValue('site_invoice_logo'),
            'site_invoice_color' => MainSiteData::getValue('site_invoice_color', '#000000'),
            'site_invoice_footer' => MainSiteData::getValue('site_invoice_footer'),
            'site_invoice_notes' => MainSiteData::getValue('site_invoice_notes'),
            'site_invoice_terms' => MainSiteData::getValue('site_invoice_terms'),
            'site_invoice_signature' => MainSiteData::getValue('site_invoice_signature'),
            'disable_check_no' => MainSiteData::getValue('disable_check_no', 0),
            'disable_check_days' => MainSiteData::getValue('disable_check_days', 0),

            // Security / Secrets
            'site_secret_key' => MainSiteData::getValue('site_secret_key'),
            'site_secret_value' => MainSiteData::getValue('site_secret_value'),
            'site_secret_validity' => MainSiteData::getValue('site_secret_validity'),
            'site_secret_url' => MainSiteData::getValue('site_secret_url'),
            'site_secret_email' => MainSiteData::getValue('site_secret_email'),

            // Log Server
            'mysql_binary_path' => MainSiteData::getValue('mysql_binary_path', ''),
            'log_server_enabled' => (bool) MainSiteData::getValue('log_server_enabled', false),
            'log_server_routers' => MainSiteData::getValue('log_server_routers', []),
            'log_retention_days' => MainSiteData::getValue('log_retention_days', 30),

            // Dynamic Web Content (MainSiteData unique)
            'hero_title' => MainSiteData::getValue('hero_title'),
            'hero_subtitle' => MainSiteData::getValue('hero_subtitle'),
            'hero_button_text' => MainSiteData::getValue('hero_button_text', 'Get Online'),
            'hero_button_link' => MainSiteData::getValue('hero_button_link'),
            'about_title' => MainSiteData::getValue('about_title'),
            'about_body' => MainSiteData::getValue('about_body'),
            'packages_section_title' => MainSiteData::getValue('packages_section_title', 'Internet Packages'),
            'footer_copyright' => MainSiteData::getValue('footer_copyright'),
            'is_active' => (bool) MainSiteData::getValue('is_active', true),
            'registration_link' => MainSiteData::getValue('registration_link'),

            'hero_slides' => MainSiteData::getValue('hero_slides', []),
            'services' => MainSiteData::getValue('services', []),
            'testimonials' => MainSiteData::getValue('testimonials', []),
            'gallery_items' => MainSiteData::getValue('gallery_items', []),
            'all_data' => MainSiteData::all()->toArray(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Master Setup')
                ->tabs([
                    Tab::make('Identity & SEO')
                        ->components([
                            Section::make('Core Brand Identity')
                                ->components([
                                    TextInput::make('site_name')->label('App Name')->required(),
                                    TextInput::make('site_title')->label('Browser Title Tag'),
                                    Select::make('site_status')->options(['active' => 'Active', 'disabled' => 'Disabled'])->default('active'),
                                    Toggle::make('site_maintenance')->label('Maintenance Mode'),
                                    Textarea::make('site_message')->placeholder('Short tagline or notice...')->rows(2),
                                ])->columns(2),
                            Section::make('Assets & Media')
                                ->components([
                                    FileUpload::make('site_logo')->image()->directory('brand'),
                                    FileUpload::make('site_icon')->label('App Icon/Logo Square')->image()->directory('brand'),
                                    FileUpload::make('site_favicon')->image()->directory('brand'),
                                ])->columns(3),
                            Section::make('Search Engine Optimization (SEO)')
                                ->components([
                                    TextInput::make('site_author'),
                                    TextInput::make('site_keywords')->placeholder('fiber, internet, broadband...'),
                                    Textarea::make('site_description')->rows(3),
                                ])->columns(2),
                        ]),

                    Tab::make('Contact & Social')
                        ->components([
                            Section::make('Office Contact Information')
                                ->components([
                                    TextInput::make('site_email')->email(),
                                    TextInput::make('site_phone'),
                                    TextInput::make('site_address'),
                                    Textarea::make('site_map')->label('Map Embed Link')->rows(2),
                                ])->columns(2),
                            Section::make('Social Media Presence')
                                ->components([
                                    TextInput::make('site_facebook')->prefix('fb.com/'),
                                    TextInput::make('site_twitter')->prefix('@'),
                                    TextInput::make('site_instagram')->prefix('ig.me/'),
                                    TextInput::make('site_whatsapp')->prefix('wa.me/'),
                                    TextInput::make('site_linkedin'),
                                    TextInput::make('site_youtube'),
                                    TextInput::make('site_pinterest'),
                                ])->columns(3),
                        ]),

                    Tab::make('Billing & Invoicing')
                        ->components([
                            Section::make('Currency & Global Controls')
                                ->components([
                                    TextInput::make('site_currency')->default('BDT'),
                                    TextInput::make('site_invoice_prefix')->default('INV-'),
                                    TextInput::make('disable_check_no')->label('Grace Limit Amount')->numeric(),
                                    TextInput::make('disable_check_days')->label('Grace Limit Days')->numeric(),
                                ])->columns(2),
                            Section::make('Invoice Design')
                                ->components([
                                    FileUpload::make('site_invoice_logo')->image()->directory('invoices'),
                                    ColorPicker::make('site_invoice_color')->default('#000000'),
                                    TextInput::make('site_invoice_footer'),
                                    Textarea::make('site_invoice_notes')->rows(2),
                                    Textarea::make('site_invoice_terms')->rows(2),
                                    FileUpload::make('site_invoice_signature')->image()->directory('invoices'),
                                ])->columns(2),
                        ]),

                    Tab::make('Security & Tech')
                        ->components([
                            Section::make('Site Secret Credentials')
                                ->components([
                                    TextInput::make('site_secret_key'),
                                    TextInput::make('site_secret_value'),
                                    TextInput::make('site_secret_validity'),
                                    TextInput::make('site_secret_url'),
                                    TextInput::make('site_secret_email'),
                                ])->columns(2),
                            Section::make('Database Configuration')
                                ->components([
                                    TextInput::make('mysql_binary_path')
                                        ->label('MySQL Binary Folder Path')
                                        ->placeholder('e.g., C:\laragon\bin\mysql\mysql-x.x\bin\\')
                                        ->helperText('Required if the backup feature fails due to mysqldump missing from PATH. Must include trailing slash!'),
                                ])->columns(1),
                            Section::make('Log Server Operations')
                                ->components([
                                    Toggle::make('log_server_enabled')->label('Stream Router Logs'),
                                    Select::make('log_server_routers')
                                        ->multiple()
                                        ->options(RouterList::pluck('router_name', 'router_name'))
                                        ->label('Log and archive for:'),
                                    TextInput::make('log_retention_days')->numeric()->label('Retention Policy (Days)'),
                                    ViewField::make('log_stats')->view('livewire.mikrotik.log-stats-embed'),
                                ])->columns(2),
                        ]),

                    Tab::make('Website Content')
                        ->components([
                            Section::make('Landing Page Hero')
                                ->components([
                                    TextInput::make('hero_title'),
                                    TextInput::make('hero_subtitle'),
                                    TextInput::make('hero_button_text'),
                                    TextInput::make('registration_link'),
                                    Repeater::make('hero_slides')
                                        ->schema([
                                            FileUpload::make('image')->image()->required(),
                                            TextInput::make('caption'),
                                        ])->columns(2)->grid(2),
                                ])->columns(2),
                            Section::make('Dynamic Modules')
                                ->components([
                                    TextInput::make('about_title'),
                                    Textarea::make('about_body')->rows(3),
                                    Repeater::make('services')
                                        ->schema([
                                            TextInput::make('icon')->default('wifi'),
                                            TextInput::make('title')->required(),
                                            TextInput::make('description'),
                                        ])->columns(3),
                                ]),
                            Section::make('Footer')
                                ->components([
                                    TextInput::make('footer_copyright'),
                                    Toggle::make('is_active')->label('Site Active Status'),
                                ])->columns(2),
                        ]),

                    Tab::make('Stored Logs')
                        ->components([
                            ViewField::make('logs_table')->view('livewire.mikrotik.log-table-master-embed'),
                        ]),

                    Tab::make('System Utilities')
                        ->components([
                            ViewField::make('system_utilities')->view('livewire.mikrotik.system-utilities-embed'),
                        ]),

                    Tab::make('Data Review')
                        ->components([
                            Section::make('Full Key-Value Store Persistence')
                                ->components([
                                    Repeater::make('all_data')
                                        ->schema([
                                            TextInput::make('type')->required(),
                                            Textarea::make('value')->rows(1),
                                        ])->columns(2)->collapsed(),
                                ]),
                        ]),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // All keys from both migrations
        $keys = [
            'site_name', 'site_title', 'site_status', 'site_maintenance', 'site_message',
            'site_logo', 'site_icon', 'site_favicon',
            'site_description', 'site_keywords', 'site_author',
            'site_email', 'site_phone', 'site_address', 'site_map',
            'site_facebook', 'site_twitter', 'site_instagram', 'site_whatsapp', 'site_linkedin', 'site_youtube', 'site_pinterest',
            'site_currency', 'site_invoice_prefix', 'site_invoice_logo', 'site_invoice_color', 'site_invoice_footer', 'site_invoice_notes', 'site_invoice_terms', 'site_invoice_signature',
            'disable_check_no', 'disable_check_days',
            'site_secret_key', 'site_secret_value', 'site_secret_validity', 'site_secret_url', 'site_secret_email',
            'mysql_binary_path', 'log_server_enabled', 'log_server_routers', 'log_retention_days',
            'hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_link', 'registration_link',
            'about_title', 'about_body', 'packages_section_title', 'testimonial_title', 'footer_copyright', 'is_active',
            'hero_slides', 'services', 'testimonials', 'gallery_items',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $state)) {
                MainSiteData::setValue($key, $state[$key]);
            }
        }

        if (isset($state['all_data'])) {
            foreach ($state['all_data'] as $item) {
                if (empty($item['type'])) {
                    continue;
                }
                MainSiteData::setValue($item['type'], $item['value']);
            }
        }

        Cache::flush();
        flash()->success('Master Setup saved. All settings and secrets migrated to universal KV store!');
    }

    public function clearCacheAction(): Action
    {
        return Action::make('clearCacheAction')
            ->label('Clear Cache')
            ->color('warning')
            ->icon('heroicon-m-bolt')
            ->requiresConfirmation()
            ->action(function () {
                Artisan::call('optimize:clear');
                flash()->success('System caches cleared successfully!');
            });
    }

    public function storageLinkAction(): Action
    {
        return Action::make('storageLinkAction')
            ->label('Storage Link')
            ->color('info')
            ->icon('heroicon-m-link')
            ->requiresConfirmation()
            ->modalDescription('This will create a symbolic link from "public/storage" to "storage/app/public". Do this once on every new server deployment so your images work!')
            ->action(function () {
                Artisan::call('storage:link');
                flash()->success(Artisan::output());
            });
    }

    public function cronSetupAction(): Action
    {
        $path = base_path();

        return Action::make('cronSetupAction')
            ->label('Cron Setup')
            ->color('gray')
            ->icon('heroicon-m-clock')
            ->modalHeading('Configure Background Tasks (Cron)')
            ->modalSubmitAction(false) // No submit button needed, just info
            ->modalCancelActionLabel('Close')
            ->modalDescription(new HtmlString('
                <p class="mb-3">For automated tasks (like auto-disabling, log polling, and router syncing) to run, you must add the following Cron Job to your server (e.g. cPanel or VPS):</p>
                <div class="p-3 bg-secondary bg-opacity-10 rounded text-wrap text-break font-monospace" style="user-select: all;">
                    * * * * * cd '.escapeshellarg($path).' && php artisan schedule:run >> /dev/null 2>&1
                </div>
                <p class="mt-3 text-sm text-muted">Set it to run <b>Every Minute (* * * * *)</b>.</p>
            '));
    }

    public function backupDatabaseAction(): Action
    {
        return Action::make('backupDatabaseAction')
            ->label('Backup Database')
            ->color('success')
            ->icon('heroicon-m-arrow-down-tray')
            ->action(function () {
                $dbName = config('database.connections.mysql.database');
                $username = config('database.connections.mysql.username');
                $password = config('database.connections.mysql.password');
                $host = config('database.connections.mysql.host');
                $port = config('database.connections.mysql.port');

                $mysqlPath = MainSiteData::getValue('mysql_binary_path', '');

                $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
                $executable = $isWindows ? 'mysqldump.exe' : 'mysqldump';

                // If user didn't specify path, try to auto-detect it
                if (! empty($mysqlPath)) {
                    $mysqlDumpCmd = escapeshellarg(rtrim($mysqlPath, '/\\').DIRECTORY_SEPARATOR.$executable);
                } else {
                    $mysqlDumpCmd = escapeshellarg(app(MainSiteSetup::class)->autoDetectMysqlPath('mysqldump'));
                }

                if (! is_dir(base_path('backups'))) {
                    mkdir(base_path('backups'), 0755, true);
                }

                $fileName = 'backup_'.date('Y_m_d_H_i_s').'_'.Str::random(5).'.sql';
                $path = base_path('backups/'.$fileName);

                $passwordStr = $password ? "--password=\"{$password}\"" : '';
                $command = "{$mysqlDumpCmd} -h {$host} -P {$port} -u {$username} {$passwordStr} {$dbName} > \"{$path}\" 2>&1";

                exec($command, $output, $returnVar);

                if ($returnVar !== 0) {
                    $errorMessage = implode('<br>', $output);
                    Log::error('Backup failed: '.$errorMessage);
                    flash()->error("<b>Backup Failed!</b><br>Error: <code>{$errorMessage}</code><br>Command run: <code style='font-size:10px;'>{$command}</code>");

                    return;
                }

                flash()->success('Database backup created successfully!');
            });
    }

    public function getBackupFiles()
    {
        $backupDir = base_path('backups');
        if (! is_dir($backupDir)) {
            return [];
        }

        $files = File::files($backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => number_format($file->getSize() / 1048576, 2).' MB',
                    'date' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                    'mtime' => $file->getMTime(),
                ];
            }
        }

        usort($backups, function ($a, $b) {
            return $b['mtime'] <=> $a['mtime']; // Newest first
        });

        return $backups;
    }

    public function downloadBackupFile(string $name): void
    {
        flash()->warning("Native file download over RouterOS API/SSH is not supported for binary .backup files. Please use WinBox or an FTP client to retrieve '{$name}' from the router.");
    }

    public function deleteBackupFile($fileName)
    {
        $path = base_path('backups/'.$fileName);
        if (file_exists($path)) {
            unlink($path);
            flash()->success("Backup {$fileName} deleted successfully!");
        }
    }

    public function restoreFromBackup($fileName)
    {
        $path = base_path('backups/'.$fileName);

        if (! file_exists($path)) {
            flash()->error('Backup file not found on disk.');

            return;
        }

        $dbName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $mysqlPath = MainSiteData::getValue('mysql_binary_path', '');

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $executable = $isWindows ? 'mysql.exe' : 'mysql';

        if (! empty($mysqlPath)) {
            $mysqlCmd = escapeshellarg(rtrim($mysqlPath, '/\\').DIRECTORY_SEPARATOR.$executable);
        } else {
            $mysqlCmd = escapeshellarg($this->autoDetectMysqlPath('mysql'));
        }

        $passwordStr = $password ? "--password=\"{$password}\"" : '';
        $command = "{$mysqlCmd} -h {$host} -P {$port} -u {$username} {$passwordStr} {$dbName} < ".escapeshellarg($path)." 2>&1";

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $errorMessage = implode('<br>', $output);
            Log::error('Restore failed: '.$errorMessage);
            flash()->error("<b>Restore Failed!</b><br>Error: <code>{$errorMessage}</code><br>Command run: <code style='font-size:10px;'>{$command}</code>");

            return;
        }

        flash()->success("Database successfully restored from {$fileName}!");
    }

    public function pollLogs(): void
    {
        try {
            $ctrl = app(MikrotikController::class);
            $enabledRouters = MainSiteData::getValue('log_server_routers', []);

            if (empty($enabledRouters)) {
                flash()->warning("No routers selected for logging in the 'Log Server Operations' section.");

                return;
            }

            $routers = RouterList::where('action', 'connected')
                ->whereIn('router_name', $enabledRouters)
                ->get();

            if ($routers->isEmpty()) {
                flash()->warning("The selected routers aren't currently connected.");

                return;
            }

            $count = 0;
            foreach ($routers as $router) {
                $logs = $ctrl->getRouterLogs($router->router_name, 100);
                if (! empty($logs)) {
                    $ctrl->storeRouterLogs($router->router_name, $logs);
                    $count += count($logs);
                }
            }

            flash()->success($count > 0 ? "Fetched and stored {$count} fresh logs from your selected routers." : 'No new entries retrieved from selected routers.');
        } catch (\Exception $e) {
            flash()->error('Failed to poll routers: '.$e->getMessage());
        }
    }

    /**
     * Attempts to auto-detect the path to a MySQL binary like mysqldump.
     * Searches standard Windows paths (Laragon, XAMPP) and Unix paths,
     * falling back to checking the system PATH.
     */
    public function autoDetectMysqlPath(string $binary = 'mysqldump'): string
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if ($isWindows) {
            $binaryWithExt = $binary.'.exe';

            // First check if it's already cleanly in PATH
            exec("where {$binaryWithExt} 2>nul", $output, $returnVar);
            if ($returnVar === 0 && ! empty($output[0])) {
                return trim($output[0]);
            }

            // Get current drive (e.g. C, D, F) to dynamically detect Laragon
            $currentDrive = strtoupper(substr(base_path(), 0, 1));
            $drivesToCheck = array_unique([$currentDrive, 'C', 'D', 'E', 'F']);

            foreach ($drivesToCheck as $drive) {
                // Laragon
                $laragonPaths = glob($drive.':\\laragon\\bin\\mysql\\*\\bin\\'.$binaryWithExt);
                if (! empty($laragonPaths) && file_exists($laragonPaths[0])) {
                    return $laragonPaths[0];
                }

                // XAMPP
                $xamppPath = $drive.':\\xampp\\mysql\\bin\\'.$binaryWithExt;
                if (file_exists($xamppPath)) {
                    return $xamppPath;
                }
            }

            return $binaryWithExt; // fallback
        }

        // Unix / Linux / macOS
        exec("which {$binary} 2>/dev/null", $output, $returnVar);
        if ($returnVar === 0 && ! empty($output[0])) {
            return trim($output[0]);
        }

        $commonUnixPaths = [
            "/usr/bin/{$binary}",
            "/usr/local/bin/{$binary}",
            "/opt/lampp/bin/{$binary}",
            "/opt/homebrew/bin/{$binary}",
        ];

        foreach ($commonUnixPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return $binary; // Fallback
    }

    public function render()
    {
        return view('livewire.main-site-setup')->layout('layouts.app');
    }
}
