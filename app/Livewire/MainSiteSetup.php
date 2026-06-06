<?php

namespace App\Livewire;

use App\Http\Controllers\MikrotikController;
use App\Models\MainSiteData;
use App\Models\RouterList;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            'portal_name' => MainSiteData::getValue('portal_name', 'Code Pagol Ltd'),
            'site_title' => MainSiteData::getValue('site_title'),
            'site_status' => MainSiteData::getValue('site_status', 'active'),
            'site_maintenance' => MainSiteData::getValue('site_maintenance', 0) ? 1 : 0,
            'site_message' => MainSiteData::getValue('site_message'),
            'portal_registration_enabled' => MainSiteData::getValue('portal_registration_enabled', 1) ? 1 : 0,
            'portal_change_password_enabled' => MainSiteData::getValue('portal_change_password_enabled', 1) ? 1 : 0,
            'portal_theme_preset' => MainSiteData::getValue('portal_theme_preset', 'indigo'),
            'theme_preset' => MainSiteData::getValue('theme_preset', 'fintech'),
            'theme_name' => MainSiteData::getValue('theme_name', 'ocean_blue'),
            'theme_primary_color' => MainSiteData::getValue('theme_primary_color', '#0284c7'),
            'theme_accent_color' => MainSiteData::getValue('theme_accent_color', '#38bdf8'),
            'theme_card_style' => MainSiteData::getValue('theme_card_style', 'glass'),
            'theme_border_radius' => MainSiteData::getValue('theme_border_radius', '16px'),
            'theme_font_size' => MainSiteData::getValue('theme_font_size', 'medium'),
            'theme_font_family' => MainSiteData::getValue('theme_font_family', 'Outfit'),
            'theme_nav_style' => MainSiteData::getValue('theme_nav_style', 'sidebar'),
            'theme_widget_style' => MainSiteData::getValue('theme_widget_style', 'glass'),
            'theme_mode' => MainSiteData::getValue('theme_mode', 'dark'),
            'theme_transparency' => MainSiteData::getValue('theme_transparency', '0.5'),
            'theme_blur' => MainSiteData::getValue('theme_blur', '16px'),
            'theme_animations' => MainSiteData::getValue('theme_animations', '1.0'),
            'theme_gradient_intensity' => MainSiteData::getValue('theme_gradient_intensity', '0.7'),

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
            'customer_id_prefix' => MainSiteData::getValue('customer_id_prefix', 'FCNET'),
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
            'log_server_enabled' => MainSiteData::getValue('log_server_enabled', 0) ? 1 : 0,
            'log_server_routers' => MainSiteData::getValue('log_server_routers', []),
            'log_retention_days' => MainSiteData::getValue('log_retention_days', 30),

            // Payment Gateways
            'payment_bkash_enabled' => MainSiteData::getValue('payment_bkash_enabled', 0) ? 1 : 0,
            'payment_bkash_base_url' => MainSiteData::getValue('payment_bkash_base_url', 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'),
            'payment_bkash_username' => MainSiteData::getValue('payment_bkash_username'),
            'payment_bkash_password' => MainSiteData::getValue('payment_bkash_password'),
            'payment_bkash_app_key' => MainSiteData::getValue('payment_bkash_app_key'),
            'payment_bkash_app_secret' => MainSiteData::getValue('payment_bkash_app_secret'),

            'payment_nagad_enabled' => MainSiteData::getValue('payment_nagad_enabled', 0) ? 1 : 0,
            'payment_nagad_base_url' => MainSiteData::getValue('payment_nagad_base_url', 'http://sandbox.nagad.com.bd:10080/remote-payment-gateway-1.0/api/dfs'),
            'payment_nagad_merchant_id' => MainSiteData::getValue('payment_nagad_merchant_id'),
            'payment_nagad_public_key' => MainSiteData::getValue('payment_nagad_public_key'),
            'payment_nagad_private_key' => MainSiteData::getValue('payment_nagad_private_key'),

            'payment_sslcommerz_enabled' => MainSiteData::getValue('payment_sslcommerz_enabled', 0) ? 1 : 0,
            'payment_sslcommerz_store_id' => MainSiteData::getValue('payment_sslcommerz_store_id'),
            'payment_sslcommerz_store_password' => MainSiteData::getValue('payment_sslcommerz_store_password'),
            'payment_sslcommerz_sandbox' => MainSiteData::getValue('payment_sslcommerz_sandbox', 1) ? 1 : 0,

            // Dynamic Web Content (MainSiteData unique)
            'hero_title' => MainSiteData::getValue('hero_title'),
            'hero_subtitle' => MainSiteData::getValue('hero_subtitle'),
            'hero_button_text' => MainSiteData::getValue('hero_button_text', 'Get Online'),
            'hero_button_link' => MainSiteData::getValue('hero_button_link'),
            'about_title' => MainSiteData::getValue('about_title'),
            'about_body' => MainSiteData::getValue('about_body'),
            'packages_section_title' => MainSiteData::getValue('packages_section_title', 'Internet Packages'),
            'footer_copyright' => MainSiteData::getValue('footer_copyright'),
            'is_active' => MainSiteData::getValue('is_active', 1) ? 1 : 0,
            'registration_link' => MainSiteData::getValue('registration_link'),

            'hero_slides' => MainSiteData::getValue('hero_slides', []),
            'services' => MainSiteData::getValue('services', []),
            'testimonials' => MainSiteData::getValue('testimonials', []),
            'gallery_items' => MainSiteData::getValue('gallery_items', []),
            'gallery_categories' => MainSiteData::getValue('gallery_categories', [
                ['key' => 'category-1', 'label' => 'Equipment'],
                ['key' => 'category-2', 'label' => 'Server'],
                ['key' => 'category-3', 'label' => 'Illustration'],
                ['key' => 'category-4', 'label' => 'Media'],
            ]),
            'valuable_clients' => MainSiteData::getValue('valuable_clients', []),
            'all_data' => MainSiteData::all()->toArray(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Master Setup')
                ->tabs([
                    Tab::make('Identity & SEO')
                        ->icon('heroicon-m-globe-alt')
                        ->components([
                            Section::make('Core Brand Identity')
                                ->icon('heroicon-m-identification')
                                ->description('Define your application\'s basic identity and operational status.')
                                ->components([
                                    TextInput::make('site_name')
                                        ->label('App Name')
                                        ->placeholder('e.g., CodePagol/CP')
                                        ->required()
                                        ->prefixIcon('heroicon-m-computer-desktop'),
                                    TextInput::make('portal_name')
                                        ->label('Portal Name')
                                        ->placeholder('e.g., Code Pagol Ltd')
                                        ->required()
                                        ->prefixIcon('heroicon-m-user'),
                                    TextInput::make('site_title')
                                        ->label('Browser Title Tag')
                                        ->placeholder('e.g., Best Broadband in Town')
                                        ->helperText('This appears in the browser tab and search results.')
                                        ->prefixIcon('heroicon-m-bookmark'),
                                    Select::make('site_status')
                                        ->options([
                                            'active' => 'Active (Live)',
                                            'disabled' => 'Disabled (Offline)',
                                        ])
                                        ->required()
                                        ->default('active')
                                        ->prefixIcon('heroicon-m-check-circle'),
                                    ToggleButtons::make('site_maintenance')
                                        ->label('Enable Maintenance Mode')
                                        ->boolean()
                                        ->grouped()
                                        ->default(0),
                                    Textarea::make('site_message')
                                        ->label('System Announcement')
                                        ->placeholder('Short tagline or notice to display to all users...')
                                        ->rows(2),
                                ])->columns(2),
                            Section::make('Portal Settings')
                                ->icon('heroicon-m-user-group')
                                ->description('Configure client portal registration and security options.')
                                ->components([
                                    ToggleButtons::make('portal_registration_enabled')
                                        ->label('Enable Client Registration')
                                        ->boolean()
                                        ->grouped()
                                        ->default(1),
                                    ToggleButtons::make('portal_change_password_enabled')
                                        ->label('Enable Change Password')
                                        ->boolean()
                                        ->grouped()
                                        ->default(1),
                                ])->columns(2),
                            Section::make('Assets & Media')
                                ->icon('heroicon-m-photo')
                                ->description('Upload your branding assets. High-quality PNGs or SVGs recommended.')
                                ->components([
                                    FileUpload::make('site_logo')
                                        ->label('Main Site Logo')
                                        ->image()
                                        ->directory('brand')
                                        ->helperText('Recommended: 190x53px transparent PNG'),
                                    FileUpload::make('site_icon')
                                        ->label('Square App Icon')
                                        ->image()
                                        ->directory('brand')
                                        ->helperText('Used for smaller UI elements (1:1 ratio)'),
                                    FileUpload::make('site_favicon')
                                        ->label('Browser Favicon')
                                        ->image()
                                        ->directory('brand')
                                        ->helperText('Standard browser tab icon (16x16 or 32x32)'),
                                ])->columns(3),
                            Section::make('Search Engine Optimization (SEO)')
                                ->icon('heroicon-m-magnifying-glass')
                                ->description('Configure how search engines index and display your website.')
                                ->components([
                                    TextInput::make('site_author')
                                        ->label('Meta Author')
                                        ->placeholder('Your Company Name')
                                        ->prefixIcon('heroicon-m-user'),
                                    TextInput::make('site_keywords')
                                        ->label('Meta Keywords')
                                        ->placeholder('fiber, internet, broadband, ISP...')
                                        ->helperText('Separate keywords with commas.')
                                        ->prefixIcon('heroicon-m-tag'),
                                    Textarea::make('site_description')
                                        ->label('Meta Description')
                                        ->placeholder('Enter a short description for search engines...')
                                        ->rows(3),
                                ])->columns(2),
                        ]),

                    Tab::make('Theme & Colors')
                        ->icon('heroicon-m-paint-brush')
                        ->components([
                            Section::make('Customer Portal Theme Settings')
                                ->icon('heroicon-m-paint-brush')
                                ->description('Choose a default theme preset and color scheme for the client portal dashboard.')
                                ->components([
                                    Select::make('portal_theme_preset')
                                        ->label('Default Portal Theme Preset')
                                        ->options([
                                            'indigo' => 'Royal Purple (Indigo/Purple/Pink)',
                                            'emerald' => 'Forest Mint (Emerald/Teal)',
                                            'blue' => 'Ocean Breeze (Blue/Sky/Cyan)',
                                            'orange' => 'Sunset Glow (Orange/Red/Pink)',
                                            'dark' => 'Midnight Slate (Slate/Dark)',
                                        ])
                                        ->required()
                                        ->default('indigo')
                                        ->prefixIcon('heroicon-m-paint-brush'),
                                ])->columns(1),

                            Section::make('Global Default Theme Customization')
                                ->icon('heroicon-m-paint-brush')
                                ->description('Configure the default styles for the client portal and the main landing page. These will serve as fallback settings if users do not customize their views.')
                                ->components([
                                    Select::make('theme_preset')
                                        ->label('UI Style Preset')
                                        ->options([
                                            'fintech' => 'Modern Fintech',
                                            'islamic' => 'Minimal Islamic',
                                            'cyber' => 'Cyber Dark',
                                            'elegant' => 'Elegant Soft',
                                            'glass' => 'Glassmorphism',
                                            'neo' => 'Neo Modern',
                                            'spiritual' => 'Calm Spiritual',
                                        ])
                                        ->required()
                                        ->default('fintech')
                                        ->reactive()
                                        ->prefixIcon('heroicon-m-sparkles')
                                        ->afterStateUpdated(fn ($state, $set) => self::applyPresetToForm($state, $set)),

                                    Select::make('theme_name')
                                        ->label('Theme Selection')
                                        ->options([
                                            'amoled' => 'AMOLED Dark',
                                            'minimal_light' => 'Minimal Light',
                                            'islamic_emerald' => 'Islamic Emerald',
                                            'ocean_blue' => 'Ocean Blue',
                                            'midnight_purple' => 'Midnight Purple',
                                            'soft_gold' => 'Soft Gold',
                                            'modern_cyan' => 'Modern Cyan',
                                            'dynamic_gradient' => 'Dynamic Gradient Theme',
                                        ])
                                        ->required()
                                        ->default('ocean_blue')
                                        ->prefixIcon('heroicon-m-swatch'),

                                    ColorPicker::make('theme_primary_color')
                                        ->label('Primary Color')
                                        ->required()
                                        ->default('#0284c7'),

                                    ColorPicker::make('theme_accent_color')
                                        ->label('Accent Color')
                                        ->required()
                                        ->default('#38bdf8'),

                                    Select::make('theme_card_style')
                                        ->label('Card Style')
                                        ->options([
                                            'flat' => 'Flat / Solid',
                                            'glass' => 'Glassmorphism',
                                            'minimal' => 'Minimal Outline',
                                            'cyber' => 'Cyber Glow',
                                            'soft' => 'Soft Shadow',
                                            'neo' => 'Neo Pop (3D)',
                                            'spiritual' => 'Spiritual Rounded',
                                        ])
                                        ->required()
                                        ->default('glass')
                                        ->prefixIcon('heroicon-m-square-2-stack'),

                                    Select::make('theme_border_radius')
                                        ->label('Border Radius')
                                        ->options([
                                            '0px' => 'Sharp (0px)',
                                            '4px' => 'Subtle (4px)',
                                            '8px' => 'Standard (8px)',
                                            '12px' => 'Medium (12px)',
                                            '16px' => 'Large (16px)',
                                            '24px' => 'Extra Large (24px)',
                                            '32px' => 'Calm Rounded (32px)',
                                        ])
                                        ->required()
                                        ->default('16px')
                                        ->prefixIcon('heroicon-m-view-columns'),

                                    Select::make('theme_font_size')
                                        ->label('Default Font Size')
                                        ->options([
                                            'small' => 'Small',
                                            'medium' => 'Medium',
                                            'large' => 'Large',
                                        ])
                                        ->required()
                                        ->default('medium')
                                        ->prefixIcon('heroicon-m-document-text'),

                                    Select::make('theme_font_family')
                                        ->label('Font Family')
                                        ->options([
                                            'Inter' => 'Inter (Modern Sans)',
                                            'Outfit' => 'Outfit (Clean Geometric)',
                                            'Plus Jakarta Sans' => 'Plus Jakarta Sans (Sleek)',
                                            'Playfair Display' => 'Playfair Display (Serif)',
                                            'Figtree' => 'Figtree (Friendly)',
                                            'Courier New' => 'Monospace (Cyber)',
                                            'Nunito' => 'Nunito (Rounded)',
                                        ])
                                        ->required()
                                        ->default('Outfit')
                                        ->prefixIcon('heroicon-m-language'),

                                    Select::make('theme_nav_style')
                                        ->label('Navigation Style')
                                        ->options([
                                            'sidebar' => 'Vertical Sidebar',
                                            'top' => 'Top Bar',
                                            'bottom' => 'Bottom Navigation',
                                            'double-top' => 'Double Top Navigation',
                                        ])
                                        ->required()
                                        ->default('sidebar')
                                        ->prefixIcon('heroicon-m-bars-3-bottom-left'),

                                    Select::make('theme_widget_style')
                                        ->label('Widget Appearance')
                                        ->options([
                                            'compact' => 'Compact',
                                            'minimal' => 'Minimal',
                                            'glass' => 'Modern Glassmorphism',
                                            'amoled' => 'AMOLED Solid',
                                            'transparent' => 'Transparent Outline',
                                        ])
                                        ->required()
                                        ->default('glass')
                                        ->prefixIcon('heroicon-m-puzzle-piece'),

                                    Select::make('theme_mode')
                                        ->label('Default Mode')
                                        ->options([
                                            'dark' => 'Dark Mode',
                                            'light' => 'Light Mode',
                                            'auto' => 'Auto System Theme',
                                            'scheduled' => 'Scheduled Theme Switching',
                                            'battery' => 'Battery Saver Theme',
                                        ])
                                        ->required()
                                        ->default('dark')
                                        ->prefixIcon('heroicon-m-sun'),

                                    TextInput::make('theme_transparency')
                                        ->label('Transparency Level')
                                        ->numeric()
                                        ->default(0.5)
                                        ->helperText('Range: 0.0 (opaque) to 1.0 (fully transparent)'),

                                    TextInput::make('theme_blur')
                                        ->label('Blur Effect (px)')
                                        ->default('16px')
                                        ->placeholder('e.g., 16px'),

                                    TextInput::make('theme_animations')
                                        ->label('Animation Intensity')
                                        ->numeric()
                                        ->default(1.0)
                                        ->helperText('Range: 0.0 (none) to 2.0 (vibrant)'),

                                    TextInput::make('theme_gradient_intensity')
                                        ->label('Gradient Intensity')
                                        ->numeric()
                                        ->default(0.7)
                                        ->helperText('Range: 0.0 to 1.0'),
                                ])->columns(3),
                        ]),

                    Tab::make('Contact & Social')
                        ->icon('heroicon-m-phone')
                        ->components([
                            Section::make('Office Contact Information')
                                ->icon('heroicon-m-building-office')
                                ->description('Displayed on the contact page and website footer.')
                                ->components([
                                    TextInput::make('site_email')
                                        ->label('Public Support Email')
                                        ->email()
                                        ->prefixIcon('heroicon-m-envelope'),
                                    TextInput::make('site_phone')
                                        ->label('Helpline Number')
                                        ->tel()
                                        ->prefixIcon('heroicon-m-phone'),
                                    TextInput::make('site_address')
                                        ->label('Physical Office Address')
                                        ->prefixIcon('heroicon-m-map-pin'),
                                    Textarea::make('site_map')
                                        ->label('Google Maps Embed URL')
                                        ->placeholder('https://www.google.com/maps/embed?pb=...')
                                        ->helperText('Paste only the src URL from the Google Maps iframe embed code.')
                                        ->rows(2),
                                ])->columns(2),
                            Section::make('Social Media Presence')
                                ->icon('heroicon-m-share')
                                ->description('Connect your social profiles for better customer engagement.')
                                ->components([
                                    TextInput::make('site_facebook')->label('Facebook')->prefix('facebook.com/')->prefixIcon('heroicon-m-link'),
                                    TextInput::make('site_twitter')->label('Twitter/X')->prefix('@')->prefixIcon('heroicon-m-link'),
                                    TextInput::make('site_instagram')->label('Instagram')->prefix('instagram.com/')->prefixIcon('heroicon-m-link'),
                                    TextInput::make('site_whatsapp')->label('WhatsApp Number')->placeholder('88017xxxxxxxx')->prefixIcon('heroicon-m-chat-bubble-left-ellipsis'),
                                    TextInput::make('site_linkedin')->label('LinkedIn')->prefixIcon('heroicon-m-link'),
                                    TextInput::make('site_youtube')->label('YouTube Channel')->prefixIcon('heroicon-m-play-circle'),
                                    TextInput::make('site_pinterest')->label('Pinterest')->prefixIcon('heroicon-m-link'),
                                ])->columns(3),
                        ]),

                    Tab::make('Billing & Invoicing')
                        ->icon('heroicon-m-credit-card')
                        ->components([
                            Section::make('Currency & Global Controls')
                                ->icon('heroicon-m-currency-dollar')
                                ->description('Global settings for billing currency and grace periods.')
                                ->components([
                                    TextInput::make('site_currency')->default('BDT')->prefixIcon('heroicon-m-banknotes'),
                                    TextInput::make('site_invoice_prefix')->default('INV-')->prefixIcon('heroicon-m-hashtag'),
                                    TextInput::make('customer_id_prefix')
                                        ->label('Customer ID Prefix')
                                        ->default('FCNET')
                                        ->placeholder('e.g., FCNET')
                                        ->required()
                                        ->prefixIcon('heroicon-m-user'),
                                    TextInput::make('disable_check_no')
                                        ->label('Grace Limit Amount')
                                        ->numeric()
                                        ->helperText('Maximum outstanding balance allowed before auto-disabling.')
                                        ->prefixIcon('heroicon-m-no-symbol'),
                                    TextInput::make('disable_check_days')
                                        ->label('Grace Limit Days')
                                        ->numeric()
                                        ->helperText('Number of days past due before auto-disabling.')
                                        ->prefixIcon('heroicon-m-clock'),
                                ])->columns(2),
                            Section::make('Invoice Design')
                                ->icon('heroicon-m-paint-brush')
                                ->description('Customize the look and feel of your generated PDF invoices.')
                                ->components([
                                    FileUpload::make('site_invoice_logo')
                                        ->label('Invoice Logo')
                                        ->image()
                                        ->directory('invoices')
                                        ->helperText('Logo displayed specifically on invoices.'),
                                    ColorPicker::make('site_invoice_color')
                                        ->label('Theme Color')
                                        ->default('#000000'),
                                    TextInput::make('site_invoice_footer')
                                        ->label('Footer Text')
                                        ->placeholder('e.g., Thank you for choosing us!'),
                                    RichEditor::make('site_invoice_terms')
                                        ->label('Terms & Conditions')
                                        ->grow(),
                                    FileUpload::make('site_invoice_signature')
                                        ->label('Authorized Signature')
                                        ->image()
                                        ->directory('invoices'),
                                ])->columns(2),
                        ]),

                    Tab::make('Payment Gateways')
                        ->icon('heroicon-m-credit-card')
                        ->components([
                            Section::make('bKash Checkout Settings')
                                ->icon('heroicon-m-credit-card')
                                ->description('Configure your bKash payment gateway credentials.')
                                ->components([
                                    ToggleButtons::make('payment_bkash_enabled')
                                        ->label('Enable bKash Gateway')
                                        ->boolean()
                                        ->grouped()
                                        ->default(0),
                                    TextInput::make('payment_bkash_base_url')
                                        ->label('bKash Base URL')
                                        ->required()
                                        ->default('https://tokenized.sandbox.bka.sh/v1.2.0-beta'),
                                    TextInput::make('payment_bkash_username')
                                        ->label('bKash Username'),
                                    TextInput::make('payment_bkash_password')
                                        ->label('bKash Password')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('payment_bkash_app_key')
                                        ->label('bKash App Key')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('payment_bkash_app_secret')
                                        ->label('bKash App Secret')
                                        ->password()
                                        ->revealable(),
                                ])->columns(2),

                            Section::make('Nagad Pay Settings')
                                ->icon('heroicon-m-credit-card')
                                ->description('Configure your Nagad payment gateway credentials.')
                                ->components([
                                    ToggleButtons::make('payment_nagad_enabled')
                                        ->label('Enable Nagad Gateway')
                                        ->boolean()
                                        ->grouped()
                                        ->default(0),
                                    TextInput::make('payment_nagad_base_url')
                                        ->label('Nagad Base URL')
                                        ->required()
                                        ->default('http://sandbox.nagad.com.bd:10080/remote-payment-gateway-1.0/api/dfs'),
                                    TextInput::make('payment_nagad_merchant_id')
                                        ->label('Nagad Merchant ID'),
                                    Textarea::make('payment_nagad_public_key')
                                        ->label('Nagad Public Key')
                                        ->rows(3),
                                    Textarea::make('payment_nagad_private_key')
                                        ->label('Merchant Private Key')
                                        ->rows(3),
                                ])->columns(2),

                            Section::make('SSLCommerz Settings')
                                ->icon('heroicon-m-credit-card')
                                ->description('Configure your SSLCommerz payment gateway credentials.')
                                ->components([
                                    ToggleButtons::make('payment_sslcommerz_enabled')
                                        ->label('Enable SSLCommerz Gateway')
                                        ->boolean()
                                        ->grouped()
                                        ->default(0),
                                    TextInput::make('payment_sslcommerz_store_id')
                                        ->label('Store ID'),
                                    TextInput::make('payment_sslcommerz_store_password')
                                        ->label('Store Password')
                                        ->password()
                                        ->revealable(),
                                    ToggleButtons::make('payment_sslcommerz_sandbox')
                                        ->label('Sandbox Mode')
                                        ->boolean()
                                        ->grouped()
                                        ->default(1),
                                ])->columns(2),
                        ]),

                    Tab::make('Security & Tech')
                        ->icon('heroicon-m-shield-check')
                        ->components([
                            Section::make('Site Secret Credentials')
                                ->icon('heroicon-m-key')
                                ->description('Sensitive credentials for internal system features and API integrations.')
                                ->components([
                                    TextInput::make('site_secret_key')->password()->revealable(),
                                    TextInput::make('site_secret_value')->password()->revealable(),
                                    TextInput::make('site_secret_validity')->placeholder('365 days'),
                                    TextInput::make('site_secret_url')->url(),
                                    TextInput::make('site_secret_email')->email(),
                                ])->columns(2),
                            Section::make('Database Configuration')
                                ->icon('heroicon-m-server')
                                ->description('System paths required for database operations like backups and restores.')
                                ->components([
                                    TextInput::make('mysql_binary_path')
                                        ->label('MySQL/MariaDB Binary Folder Path')
                                        ->placeholder('e.g., C:\laragon\bin\mysql\mysql-x.x\bin\\')
                                        ->helperText('Required if the backup feature fails due to mysqldump missing from PATH. Must include trailing slash!')
                                        ->prefixIcon('heroicon-m-folder-open'),
                                ])->columns(1),
                            Section::make('Log Server Operations')
                                ->icon('heroicon-m-document-text')
                                ->description('Configure real-time log streaming from your MikroTik routers to this server.')
                                ->components([
                                    ToggleButtons::make('log_server_enabled')
                                        ->label('Enable Remote Log Streaming')
                                        ->boolean()
                                        ->grouped()
                                        ->default(0)
                                        ->helperText('Enables the background listener for incoming router logs.'),
                                    Select::make('log_server_routers')
                                        ->multiple()
                                        ->options(RouterList::pluck('router_name', 'router_name'))
                                        ->label('Capture logs for:')
                                        ->searchable(),
                                    TextInput::make('log_retention_days')
                                        ->label('Auto-Delete Logs After (Days)')
                                        ->numeric()
                                        ->prefixIcon('heroicon-m-trash'),
                                    ViewField::make('log_stats')->view('livewire.mikrotik.log-stats-embed'),
                                ])->columns(2),
                        ]),

                    Tab::make('Website Content')
                        ->icon('heroicon-m-window')
                        ->components([
                            Section::make('Landing Page Hero')
                                ->icon('heroicon-m-star')
                                ->description('Manage the main slider and call-to-action area of your homepage.')
                                ->components([
                                    TextInput::make('hero_title')
                                        ->label('Main Headline')
                                        ->placeholder('e.g., Ultra Fast Fiber Internet'),
                                    TextInput::make('hero_subtitle')
                                        ->label('Sub-headline')
                                        ->placeholder('e.g., Experience the best connectivity in the city.'),
                                    TextInput::make('hero_button_text')
                                        ->label('Action Button Label')
                                        ->placeholder('e.g., View Packages'),
                                    TextInput::make('registration_link')
                                        ->label('Customer Portal Link')
                                        ->url(),
                                ])->columns(2),
                            Section::make('Hero Slider Images')
                                ->icon('heroicon-m-star')
                                ->description('Add images for your hero slider. These will be displayed in a carousel on the homepage.')
                                ->components([
                                    Repeater::make('hero_slides')
                                        ->label('Hero Slider Images')
                                        ->schema([
                                            FileUpload::make('image')
                                                ->image()
                                                ->imageEditor()
                                                ->imageAspectRatio('8:3')
                                                ->automaticallyOpenImageEditorForAspectRatio()
                                                ->automaticallyResizeImagesMode('cover')
                                                ->automaticallyResizeImagesToWidth('1920')
                                                ->automaticallyResizeImagesToHeight('720')
                                                ->rules(['image', 'max:20480'])
                                                ->required()
                                                ->directory('hero'),
                                            TextInput::make('caption')->placeholder('Slide caption...'),
                                        ])->grid(3),
                                ]),
                            Section::make('Dynamic Modules')
                                ->icon('heroicon-m-square-3-stack-3d')
                                ->description('Edit information about your company and highlight your key services.')
                                ->components([
                                    TextInput::make('about_title')->label('About Section Title'),
                                    Textarea::make('about_body')->label('About Company Content')->rows(3),
                                    Repeater::make('services')
                                        ->label('Our Services')
                                        ->schema([
                                            TextInput::make('icon')
                                                ->label('Bootstrap Icon Class')
                                                ->placeholder('e.g., bi bi-wifi, bi bi-shield-check, bi bi-speedometer')
                                                ->default('bi bi-wifi'),
                                            TextInput::make('title')->label('Service Title')->required(),
                                            TextInput::make('description')->label('Short Description'),
                                        ])->columns(3),
                                    Repeater::make('valuable_clients')
                                        ->label('Valuable Clients')
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Client Name')
                                                ->required(),
                                            FileUpload::make('logo')
                                                ->label('Client Logo')
                                                ->image()
                                                ->directory('clients')
                                                ->helperText('If uploaded, the logo will be shown. If not, the name will be used.'),
                                            TextInput::make('link')
                                                ->label('Client Website/Link')
                                                ->url()
                                                ->placeholder('https://...'),
                                        ])->columns(3)->grid(3),
                                ]),

                            Section::make('Gallery')
                                ->icon('heroicon-m-photo')
                                ->description('Manage gallery images shown on the public homepage. Upload images, set a caption and category.')
                                ->components([
                                    Repeater::make('gallery_categories')
                                        ->label('Gallery Categories')
                                        ->schema([
                                            TextInput::make('key')
                                                ->label('Key')
                                                ->required()
                                                ->placeholder('Unique key used in gallery items, e.g. category-1')
                                                ->inlineLabel(),
                                            TextInput::make('label')
                                                ->label('Label')
                                                ->required()
                                                ->placeholder('Human readable label shown on filter buttons')
                                                ->inlineLabel(),
                                        ])
                                        ->columns(2)
                                        ->grid(2)
                                        ->extraAttributes(['class' => 'p-0 m-0'])
                                        ->helperText('Define categories available for gallery items. New categories uploaded from the public uploader will be added automatically.')
                                        ->deleteAction(fn (Action $action) => $action->iconButton()->icon('heroicon-m-trash'))
                                        ->moveUpAction(fn (Action $action) => $action->iconButton()->icon('heroicon-m-chevron-up'))
                                        ->moveDownAction(fn (Action $action) => $action->iconButton()->icon('heroicon-m-chevron-down'))
                                        ->reactive()
                                        ->afterStateUpdated(fn () => $this->save()),

                                    Repeater::make('gallery_items')
                                        ->label('Gallery Items')
                                        ->reorderable()
                                        ->schema([
                                            FileUpload::make('image')
                                                ->label('Image')
                                                ->image()
                                                ->directory('gallery')
                                                ->required(),
                                            TextInput::make('caption')
                                                ->label('Caption')
                                                ->placeholder('Optional caption'),
                                            Select::make('category')
                                                ->label('Category')
                                                ->options(fn () => collect(MainSiteData::getValue('gallery_categories', []))
                                                    ->mapWithKeys(fn ($c) => [($c['key'] ?? $c['label'] ?? '') => $c['label'] ?? $c['key'] ?? ''])
                                                    ->toArray())
                                                ->default('category-1'),
                                        ])
                                        ->columns(1)
                                        ->grid(1)
                                        ->helperText('Add, reorder, or remove gallery images. Files are stored in the `gallery` folder. Use drag‑and‑drop to change order.'),
                                ]),

                            Section::make('Footer & Global')
                                ->icon('heroicon-m-bars-3')
                                ->components([
                                    TextInput::make('footer_copyright')
                                        ->label('Copyright Text')
                                        ->placeholder('e.g., © 2024 Your Company. All rights reserved.'),
                                    ToggleButtons::make('is_active')
                                        ->boolean()
                                        ->grouped()
                                        ->label('Public Website Visible')
                                        ->default(1)
                                        ->helperText('Turn off to hide the public website while keeping the admin panel active.'),
                                ])->columns(2),
                        ]),

                    Tab::make('Stored Logs')
                        ->icon('heroicon-m-list-bullet')
                        ->components([
                            ViewField::make('logs_table')->view('livewire.mikrotik.log-table-master-embed'),
                        ]),

                    Tab::make('System Utilities')
                        ->icon('heroicon-m-wrench-screwdriver')
                        ->components([
                            ViewField::make('system_utilities')->view('livewire.mikrotik.system-utilities-embed'),
                        ]),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        try {
            $state = $this->form->getState();
            Log::debug('MainSiteSetup save state: '.json_encode([
                'payment_bkash_enabled' => $state['payment_bkash_enabled'] ?? 'not_in_state',
                'payment_nagad_enabled' => $state['payment_nagad_enabled'] ?? 'not_in_state',
                'payment_sslcommerz_enabled' => $state['payment_sslcommerz_enabled'] ?? 'not_in_state',
            ]));
        } catch (ValidationException $e) {
            Log::error('MainSiteSetup validation failed: '.json_encode($e->errors()));
            flash()->error('Validation failed: '.implode(', ', Arr::flatten($e->errors())));
            throw $e;
        }

        // All keys from both migrations
        $keys = [
            'site_name', 'portal_name', 'site_title', 'site_status', 'site_maintenance', 'site_message',
            'portal_theme_preset',
            'portal_registration_enabled', 'portal_change_password_enabled',
            'site_logo', 'site_icon', 'site_favicon',
            'site_description', 'site_keywords', 'site_author',
            'site_email', 'site_phone', 'site_address', 'site_map',
            'site_facebook', 'site_twitter', 'site_instagram', 'site_whatsapp', 'site_linkedin', 'site_youtube', 'site_pinterest',
            'site_currency', 'site_invoice_prefix', 'customer_id_prefix', 'site_invoice_logo', 'site_invoice_color', 'site_invoice_footer', 'site_invoice_notes', 'site_invoice_terms', 'site_invoice_signature',
            'disable_check_no', 'disable_check_days',
            'site_secret_key', 'site_secret_value', 'site_secret_validity', 'site_secret_url', 'site_secret_email',
            'mysql_binary_path', 'log_server_enabled', 'log_server_routers', 'log_retention_days',
            'hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_link', 'registration_link',
            'about_title', 'about_body', 'packages_section_title', 'testimonial_title', 'footer_copyright', 'is_active',
            'hero_slides', 'services', 'testimonials', 'gallery_items', 'gallery_categories', 'valuable_clients',
            'payment_bkash_enabled', 'payment_bkash_base_url', 'payment_bkash_username', 'payment_bkash_password', 'payment_bkash_app_key', 'payment_bkash_app_secret',
            'payment_nagad_enabled', 'payment_nagad_base_url', 'payment_nagad_merchant_id', 'payment_nagad_public_key', 'payment_nagad_private_key',
            'payment_sslcommerz_enabled', 'payment_sslcommerz_store_id', 'payment_sslcommerz_store_password', 'payment_sslcommerz_sandbox',
            'theme_preset', 'theme_name', 'theme_primary_color', 'theme_accent_color', 'theme_card_style',
            'theme_border_radius', 'theme_font_size', 'theme_font_family', 'theme_nav_style',
            'theme_widget_style', 'theme_mode', 'theme_transparency', 'theme_blur', 'theme_animations', 'theme_gradient_intensity',
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
                // Don't overwrite keys that were already handled above
                if (in_array($item['type'], $keys)) {
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
        $command = "{$mysqlCmd} -h {$host} -P {$port} -u {$username} {$passwordStr} {$dbName} < ".escapeshellarg($path).' 2>&1';

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

            flash()->success($count > 0 ? "Fetched {$count} fresh logs from your selected routers." : 'No new entries retrieved from selected routers.');
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

    public static function applyPresetToForm(string $preset, callable $set): void
    {
        $presets = [
            'fintech' => [
                'theme_name' => 'ocean_blue',
                'theme_primary_color' => '#00f2fe',
                'theme_accent_color' => '#4facfe',
                'theme_card_style' => 'flat',
                'theme_border_radius' => '12px',
                'theme_font_size' => 'medium',
                'theme_font_family' => 'Outfit',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'compact',
                'theme_mode' => 'dark',
                'theme_transparency' => 0.05,
                'theme_blur' => '4px',
                'theme_animations' => 1.0,
                'theme_gradient_intensity' => 0.9,
            ],
            'islamic' => [
                'theme_name' => 'islamic_emerald',
                'theme_primary_color' => '#065f46',
                'theme_accent_color' => '#10b981',
                'theme_card_style' => 'minimal',
                'theme_border_radius' => '24px',
                'theme_font_size' => 'medium',
                'theme_font_family' => 'Inter',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'minimal',
                'theme_mode' => 'dark',
                'theme_transparency' => 0.1,
                'theme_blur' => '8px',
                'theme_animations' => 0.8,
                'theme_gradient_intensity' => 0.4,
            ],
            'cyber' => [
                'theme_name' => 'amoled',
                'theme_primary_color' => '#00ffcc',
                'theme_accent_color' => '#ff007f',
                'theme_card_style' => 'cyber',
                'theme_border_radius' => '0px',
                'theme_font_size' => 'medium',
                'theme_font_family' => 'Courier New',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'amoled',
                'theme_mode' => 'dark',
                'theme_transparency' => 0.0,
                'theme_blur' => '0px',
                'theme_animations' => 1.5,
                'theme_gradient_intensity' => 1.0,
            ],
            'elegant' => [
                'theme_name' => 'minimal_light',
                'theme_primary_color' => '#f43f5e',
                'theme_accent_color' => '#fda4af',
                'theme_card_style' => 'soft',
                'theme_border_radius' => '16px',
                'theme_font_size' => 'medium',
                'theme_font_family' => 'Plus Jakarta Sans',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'minimal',
                'theme_mode' => 'light',
                'theme_transparency' => 0.1,
                'theme_blur' => '6px',
                'theme_animations' => 0.6,
                'theme_gradient_intensity' => 0.5,
            ],
            'glass' => [
                'theme_name' => 'dynamic_gradient',
                'theme_primary_color' => '#ffffff',
                'theme_accent_color' => '#00f2fe',
                'theme_card_style' => 'glass',
                'theme_border_radius' => '24px',
                'theme_font_size' => 'medium',
                'theme_font_family' => 'Outfit',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'glass',
                'theme_mode' => 'dark',
                'theme_transparency' => 0.6,
                'theme_blur' => '24px',
                'theme_animations' => 1.2,
                'theme_gradient_intensity' => 0.9,
            ],
            'neo' => [
                'theme_name' => 'midnight_purple',
                'theme_primary_color' => '#4f46e5',
                'theme_accent_color' => '#06b6d4',
                'theme_card_style' => 'neo',
                'theme_border_radius' => '12px',
                'theme_font_size' => 'medium',
                'theme_font_family' => 'Outfit',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'glass',
                'theme_mode' => 'dark',
                'theme_transparency' => 0.2,
                'theme_blur' => '10px',
                'theme_animations' => 1.0,
                'theme_gradient_intensity' => 0.85,
            ],
            'spiritual' => [
                'theme_name' => 'soft_gold',
                'theme_primary_color' => '#0f766e',
                'theme_accent_color' => '#0d9488',
                'theme_card_style' => 'spiritual',
                'theme_border_radius' => '32px',
                'theme_font_size' => 'large',
                'theme_font_family' => 'Playfair Display',
                'theme_nav_style' => 'sidebar',
                'theme_widget_style' => 'transparent',
                'theme_mode' => 'dark',
                'theme_transparency' => 0.35,
                'theme_blur' => '16px',
                'theme_animations' => 0.5,
                'theme_gradient_intensity' => 0.6,
            ],
        ];

        if (isset($presets[$preset])) {
            foreach ($presets[$preset] as $key => $value) {
                $set($key, $value);
            }
        }
    }

    public function render()
    {
        return view('livewire.main-site-setup')->layout('layouts.app');
    }
}
