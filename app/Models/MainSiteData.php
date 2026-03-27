<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MainSiteData extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_link', 'hero_slides',
        'about_tagline', 'about_title', 'about_body', 'services',
        'packages_section_title', 'packages_section_subtitle', 'registration_link',
        'gallery_items',
        'team_title', 'team_subtitle', 'team_members',
        'blog_title', 'blog_subtitle', 'blog_posts',
        'testimonial_title', 'testimonial_subtitle', 'testimonials',
        'contact_title', 'contact_subtitle', 'google_map_embed',
        'social_facebook', 'social_twitter', 'social_instagram', 'social_youtube', 'social_whatsapp',
        'footer_copyright', 'is_active',
    ];

    protected $casts = [
        'hero_slides'   => 'array',
        'services'      => 'array',
        'gallery_items' => 'array',
        'team_members'  => 'array',
        'blog_posts'    => 'array',
        'testimonials'  => 'array',
        'is_active'     => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('main_site_data'));
        static::updated(fn() => Cache::forget('main_site_data'));
    }

    public static function getActive(): ?self
    {
        return Cache::rememberForever('main_site_data', function () {
            return self::where('is_active', true)->first();
        });
    }
}
