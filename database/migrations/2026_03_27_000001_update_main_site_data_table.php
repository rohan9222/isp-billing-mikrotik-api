<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main_site_data', function (Blueprint $table) {
            // Hero Section
            $table->string('hero_title')->nullable()->after('id');
            $table->string('hero_subtitle')->nullable()->after('hero_title');
            $table->string('hero_button_text')->nullable()->default('Get Online Register')->after('hero_subtitle');
            $table->string('hero_button_link')->nullable()->after('hero_button_text');
            $table->json('hero_slides')->nullable()->after('hero_button_link'); // [{image, caption}]

            // About / Features Section
            $table->string('about_tagline')->nullable()->after('hero_slides');
            $table->string('about_title')->nullable()->after('about_tagline');
            $table->text('about_body')->nullable()->after('about_title');
            $table->json('services')->nullable()->after('about_body'); // [{icon, title, description}]

            // Packages Section
            $table->string('packages_section_title')->nullable()->default('INTERNET PACKAGE PLAN')->after('services');
            $table->string('packages_section_subtitle')->nullable()->after('packages_section_title');
            $table->string('registration_link')->nullable()->after('packages_section_subtitle');

            // Gallery Section
            $table->json('gallery_items')->nullable()->after('registration_link'); // [{image, category, caption}]

            // Team Section
            $table->string('team_title')->nullable()->default('CREATIVE TEAM')->after('gallery_items');
            $table->string('team_subtitle')->nullable()->after('team_title');
            $table->json('team_members')->nullable()->after('team_subtitle'); // [{name, role, image, bio}]

            // Blog Section
            $table->string('blog_title')->nullable()->default('Blog')->after('team_members');
            $table->string('blog_subtitle')->nullable()->after('blog_title');
            $table->json('blog_posts')->nullable()->after('blog_subtitle'); // [{title, image, date, author, excerpt, link}]

            // Testimonials Section
            $table->string('testimonial_title')->nullable()->default('TESTIMONIAL')->after('blog_posts');
            $table->string('testimonial_subtitle')->nullable()->after('testimonial_title');
            $table->json('testimonials')->nullable()->after('testimonial_subtitle'); // [{name, image, message}]

            // Contact Section
            $table->string('contact_title')->nullable()->default('CONTACT US')->after('testimonials');
            $table->string('contact_subtitle')->nullable()->after('contact_title');
            $table->text('google_map_embed')->nullable()->after('contact_subtitle');

            // Social Links
            $table->string('social_facebook')->nullable()->after('google_map_embed');
            $table->string('social_twitter')->nullable()->after('social_facebook');
            $table->string('social_instagram')->nullable()->after('social_twitter');
            $table->string('social_youtube')->nullable()->after('social_instagram');
            $table->string('social_whatsapp')->nullable()->after('social_youtube');

            // Footer
            $table->string('footer_copyright')->nullable()->after('social_whatsapp');

            // Status
            $table->boolean('is_active')->default(true)->after('footer_copyright');
        });
    }

    public function down(): void
    {
        Schema::table('main_site_data', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
