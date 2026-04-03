<?php

namespace App\Livewire;

use App\Http\Controllers\MikrotikController;
use App\Models\MainSiteData;
use App\Models\RouterList;
use App\Models\SiteSetting;
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
use Illuminate\Support\Facades\Cache;
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
            'log_server_enabled', 'log_server_routers', 'log_retention_days',
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

    public function render()
    {
        return view('livewire.main-site-setup')->layout('layouts.app');
    }
}
