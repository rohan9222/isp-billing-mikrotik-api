<?php

namespace App\Livewire;

use App\Models\MainSiteData;
use App\Models\PackageList;
use App\Models\SiteSetting;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class MainSiteSetup extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public function mount(): void
    {
        if (! hasAccess(['Super Admin'], ['site-setup'])) {
            abort(403, 'Unauthorized action.');
        }

        $site = MainSiteData::first();
        $settings = SiteSetting::first();

        $this->form->fill([
            // Identity
            'site_name'        => $settings?->site_name,
            'site_logo'        => $settings?->site_logo,
            'site_favicon'     => $settings?->site_favicon,
            'site_description' => $settings?->site_description,
            
            // Site Data
            'hero_title'               => $site?->hero_title,
            'hero_subtitle'            => $site?->hero_subtitle,
            'hero_button_text'         => $site?->hero_button_text,
            'hero_button_link'         => $site?->hero_button_link,
            'about_tagline'            => $site?->about_tagline,
            'about_title'              => $site?->about_title,
            'about_body'               => $site?->about_body,
            'packages_section_title'   => $site?->packages_section_title,
            'packages_section_subtitle' => $site?->packages_section_subtitle,
            'team_title'               => $site?->team_title,
            'team_subtitle'            => $site?->team_subtitle,
            'blog_title'               => $site?->blog_title,
            'blog_subtitle'            => $site?->blog_subtitle,
            'testimonial_title'        => $site?->testimonial_title,
            'testimonial_subtitle'     => $site?->testimonial_subtitle,
            'contact_title'            => $site?->contact_title,
            'contact_subtitle'         => $site?->contact_subtitle,
            'google_map_embed'         => $site?->google_map_embed,
            'social_facebook'          => $site?->social_facebook,
            'social_twitter'           => $site?->social_twitter,
            'social_youtube'           => $site?->social_youtube,
            'social_whatsapp'          => $site?->social_whatsapp,
            'footer_copyright'         => $site?->footer_copyright,
            'is_active'                => $site?->is_active ?? true,

            // Repeaters
            'hero_slides'   => $site?->hero_slides ?? [],
            'services'      => $site?->services ?? [],
            'team_members'  => $site?->team_members ?? [],
            'testimonials'  => $site?->testimonials ?? [],
            'blog_posts'    => $site?->blog_posts ?? [],
            'gallery_items' => $site?->gallery_items ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Site Management')
                    ->tabs([
                        Tab::make('General & Identity')
                            ->components([
                                        Section::make('Core Identity')
                                    ->components([
                                        TextInput::make('site_name')->label('Site Name')->required(),
                                        FileUpload::make('site_logo')->label('Site Logo')->image()->disk('public')->directory('main-site')->visibility('public'),
                                        FileUpload::make('site_favicon')->label('Favicon')->image()->disk('public')->directory('main-site')->visibility('public'),
                                        Textarea::make('site_description')->label('Meta Description')->rows(2),
                                    ])->columns(['md' => 2]),
                                Section::make('Contact & Footer')
                                    ->components([
                                        TextInput::make('contact_title'),
                                        TextInput::make('contact_subtitle'),
                                        TextInput::make('footer_copyright'),
                                        Toggle::make('is_active')->label('Site Active Status')->default(true),
                                        Textarea::make('google_map_embed')->label('Google Map Embed URL (src only)')->rows(2),
                                    ])->columns(['md' => 2]),
                                Section::make('Social Links')
                                    ->components([
                                        TextInput::make('social_facebook')->prefix('FB'),
                                        TextInput::make('social_whatsapp')->prefix('WA'),
                                        TextInput::make('social_youtube')->prefix('YT'),
                                        TextInput::make('social_twitter')->prefix('TW'),
                                        TextInput::make('social_instagram')->prefix('IG'),
                                    ])->columns(['md' => 3]),
                            ]),

                        Tab::make('Hero & About')
                            ->components([
                                Section::make('Hero Main')
                                    ->components([
                                        TextInput::make('hero_title'),
                                        TextInput::make('hero_subtitle'),
                                        TextInput::make('hero_button_text')->label('Hero Button Text'),
                                        TextInput::make('hero_button_link')->label('Hero Button Link (Manual)'),
                                        TextInput::make('registration_link')->label('Registration Page URL')->placeholder('/register'),
                                    ])->columns(['md' => 3]),
                                Repeater::make('hero_slides')
                                    ->schema([
                                        FileUpload::make('image')->label('Slide Image')->image()->required()->disk('public')->directory('main-site/slides'),
                                        TextInput::make('caption'),
                                    ])->columns(['md' => 2])->grid(['md' => 2]),
                                Section::make('About Section')
                                    ->components([
                                        TextInput::make('about_tagline'),
                                        TextInput::make('about_title'),
                                        Textarea::make('about_body')->rows(3),
                                    ]),
                            ]),

                        Tab::make('Dynamic Sections')
                            ->components([
                                Section::make('Packages Section')
                                    ->components([
                                        TextInput::make('packages_section_title'),
                                        TextInput::make('packages_section_subtitle'),
                                    ])->columns(['md' => 2]),
                                Section::make('Testimonials Section')
                                    ->components([
                                        TextInput::make('testimonial_title'),
                                        TextInput::make('testimonial_subtitle'),
                                        Repeater::make('testimonials')
                                            ->schema([
                                                FileUpload::make('image')->label('Client Photo')->image()->disk('public')->directory('main-site/testimonials'),
                                                TextInput::make('name')->required(),
                                                TextInput::make('message')->required(),
                                            ])->columns(['md' => 2])->grid(['md' => 2])->collapsed(),
                                    ]),
                                Section::make('Team Section')
                                    ->components([
                                        TextInput::make('team_title'),
                                        TextInput::make('team_subtitle'),
                                        Repeater::make('team_members')
                                            ->schema([
                                                FileUpload::make('image')->label('Photo')->image()->required()->disk('public')->directory('main-site/team'),
                                                TextInput::make('name')->required(),
                                                TextInput::make('role'),
                                                TextInput::make('bio'),
                                            ])->columns(['md' => 2])->grid(['md' => 2])->collapsed(),
                                    ]),
                                Section::make('Blog Section')
                                    ->components([
                                        TextInput::make('blog_title'),
                                        TextInput::make('blog_subtitle'),
                                        Repeater::make('blog_posts')
                                            ->schema([
                                                FileUpload::make('image')->label('Thumbnail')->image()->disk('public')->directory('main-site/blog'),
                                                TextInput::make('title')->required(),
                                                DatePicker::make('date')->default(now()),
                                                TextInput::make('excerpt'),
                                            ])->columns(['md' => 2])->grid(['md' => 2])->collapsed(),
                                    ]),
                                Section::make('Services')
                                    ->components([
                                        Repeater::make('services')
                                            ->schema([
                                                TextInput::make('icon')->label('Icon Class (FA/BI)')->default('fa-solid fa-wifi'),
                                                TextInput::make('title')->required(),
                                                TextInput::make('description'),
                                            ])->columns(['md' => 3])->grid(['md' => 1]),
                                    ]),
                                Section::make('Gallery')
                                    ->components([
                                        Repeater::make('gallery_items')
                                            ->schema([
                                                FileUpload::make('image')->image()->required()->disk('public')->directory('main-site/gallery'),
                                                TextInput::make('caption'),
                                                Select::make('category')
                                                    ->options([
                                                        'category-1' => 'Equipment',
                                                        'category-2' => 'Server',
                                                        'category-3' => 'Illustration',
                                                        'category-4' => 'Media',
                                                    ])->default('category-1'),
                                            ])->columns(['md' => 3])->grid(['md' => 2])->collapsed(),
                                    ]),
                            ]),

                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // 1. Update Site Settings
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name'        => $state['site_name'],
                'site_logo'        => $state['site_logo'],
                'site_favicon'     => $state['site_favicon'],
                'site_description' => $state['site_description'],
            ]
        );

        // 2. Update Main Site Content
        MainSiteData::updateOrCreate(
            ['id' => 1],
            [
                'hero_title'               => $state['hero_title'] ?? null,
                'hero_subtitle'            => $state['hero_subtitle'] ?? null,
                'hero_button_text'         => $state['hero_button_text'] ?? null,
                'hero_button_link'         => $state['hero_button_link'] ?? null,
                'registration_link'        => $state['registration_link'] ?? null,
                'about_tagline'            => $state['about_tagline'] ?? null,
                'about_title'              => $state['about_title'] ?? null,
                'about_body'               => $state['about_body'] ?? null,
                'packages_section_title'   => $state['packages_section_title'] ?? null,
                'packages_section_subtitle' => $state['packages_section_subtitle'] ?? null,
                'team_title'               => $state['team_title'] ?? null,
                'team_subtitle'            => $state['team_subtitle'] ?? null,
                'blog_title'               => $state['blog_title'] ?? null,
                'blog_subtitle'            => $state['blog_subtitle'] ?? null,
                'testimonial_title'        => $state['testimonial_title'] ?? null,
                'testimonial_subtitle'     => $state['testimonial_subtitle'] ?? null,
                'contact_title'            => $state['contact_title'] ?? null,
                'contact_subtitle'         => $state['contact_subtitle'] ?? null,
                'google_map_embed'         => $state['google_map_embed'] ?? null,
                'social_facebook'          => $state['social_facebook'] ?? null,
                'social_twitter'           => $state['social_twitter'] ?? null,
                'social_instagram'         => $state['social_instagram'] ?? null,
                'social_youtube'           => $state['social_youtube'] ?? null,
                'social_whatsapp'          => $state['social_whatsapp'] ?? null,
                'footer_copyright'         => $state['footer_copyright'] ?? null,
                'is_active'                => $state['is_active'] ?? true,
                'hero_slides'              => $state['hero_slides'] ?? [],
                'services'                 => $state['services'] ?? [],
                'team_members'             => $state['team_members'] ?? [],
                'testimonials'             => $state['testimonials'] ?? [],
                'blog_posts'               => $state['blog_posts'] ?? [],
                'gallery_items'            => $state['gallery_items'] ?? [],
            ]
        );


        Cache::forget('main_site_data');
        Cache::forget('site_settings');

        flash()->success('Full site configuration saved successfully!');
    }

    public function render()
    {
        return view('livewire.main-site-setup')->layout('layouts.app');
    }
}
