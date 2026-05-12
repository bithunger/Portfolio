<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'owner_name',
    'headline',
    'tagline',
    'hero_panel_label',
    'hero_panel_text',
    'bio',
    'portrait_url',
    'resume_url',
    'email',
    'phone',
    'location',
    'availability',
    'seo_title',
    'seo_description',
    'github_url',
    'linkedin_url',
    'twitter_url',
    'dribbble_url',
    'facebook_url',
    'whatsapp_url',
    'website_url',
    'accent_color',
    'frontend_background_color',
    'backend_background_color',
    'font_family',
    'frontend_logo_url',
    'backend_logo_url',
    'favicon_url',
    'home_section_order',
])]
class Profile extends Model
{
    public const HOME_SECTIONS = [
        'intro' => 'Intro',
        'services' => 'Services',
        'experience' => 'Experience',
        'education' => 'Education',
        'skills' => 'Skills',
        'projects' => 'Projects',
        'publications' => 'Publications',
        'testimonials' => 'Testimonials',
    ];

    protected $casts = [
        'home_section_order' => 'array',
    ];

    public static function homeSectionOptions(): array
    {
        return self::HOME_SECTIONS;
    }

    public function orderedHomeSections(): array
    {
        $savedSections = is_array($this->home_section_order) ? $this->home_section_order : [];
        $validSections = array_keys(self::HOME_SECTIONS);
        $orderedSections = collect($savedSections)
            ->filter(fn ($section) => in_array($section, $validSections, true))
            ->unique()
            ->values();

        return $orderedSections
            ->merge(collect($validSections)->diff($orderedSections))
            ->values()
            ->all();
    }

    public static function fontOptions(): array
    {
        return [
            'elegant' => [
                'label' => 'Elegant editorial',
                'body' => 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
                'display' => '"Playfair Display", Georgia, serif',
            ],
            'inter' => [
                'label' => 'Clean Inter',
                'body' => 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
                'display' => 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            ],
            'manrope' => [
                'label' => 'Modern Manrope',
                'body' => 'Manrope, Inter, ui-sans-serif, system-ui, sans-serif',
                'display' => 'Manrope, Inter, ui-sans-serif, system-ui, sans-serif',
            ],
            'poppins' => [
                'label' => 'Polished Poppins',
                'body' => 'Poppins, Inter, ui-sans-serif, system-ui, sans-serif',
                'display' => 'Poppins, Inter, ui-sans-serif, system-ui, sans-serif',
            ],
            'lora' => [
                'label' => 'Warm Lora',
                'body' => 'Inter, ui-sans-serif, system-ui, sans-serif',
                'display' => 'Lora, Georgia, serif',
            ],
        ];
    }

    public function fontConfig(): array
    {
        return self::fontOptions()[$this->font_family ?: 'elegant'] ?? self::fontOptions()['elegant'];
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->owner_name))
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->take(2)
            ->implode('');
    }

    public static function site(): self
    {
        return self::query()->firstOrCreate(
            ['id' => 1],
            [
                'owner_name' => 'Your Name',
                'headline' => 'Creative developer building polished digital products.',
                'email' => 'hello@example.com',
                'accent_color' => '#0f766e',
                'frontend_background_color' => '#f7f8f6',
                'backend_background_color' => '#f7f8f6',
                'font_family' => 'elegant',
                'home_section_order' => array_keys(self::HOME_SECTIONS),
            ],
        );
    }
}
