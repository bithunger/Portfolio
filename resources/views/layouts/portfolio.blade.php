<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $fontConfig = $profile->fontConfig();
    $normalizeHex = function (?string $value, string $fallback): string {
        $value = trim((string) $value);
        if (preg_match('/^#?([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value, $matches)) {
            $hex = $matches[1];
            if (strlen($hex) === 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }

            return '#'.strtolower($hex);
        }

        return $fallback;
    };
    $hexLuminance = function (string $hex): float {
        $hex = ltrim($hex, '#');
        $rgb = [
            hexdec(substr($hex, 0, 2)) / 255,
            hexdec(substr($hex, 2, 2)) / 255,
            hexdec(substr($hex, 4, 2)) / 255,
        ];
        $linear = array_map(fn ($value) => $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4, $rgb);

        return ($linear[0] * 0.2126) + ($linear[1] * 0.7152) + ($linear[2] * 0.0722);
    };
    $paperColor = $normalizeHex($profile->frontend_background_color ?: '#f7f8f6', '#f7f8f6');
    $accentColor = $normalizeHex($profile->accent_color ?: '#0f766e', '#0f766e');
    $paperIsDark = $hexLuminance($paperColor) < 0.42;
    $accentIsLight = $hexLuminance($accentColor) > 0.55;
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $profile->seo_title ?: $profile->owner_name.' Portfolio')</title>
    <meta name="description" content="@yield('meta_description', $profile->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($profile->bio ?: $profile->headline), 155))">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;650;750;850&family=Lora:wght@500;650;700&family=Manrope:wght@400;500;650;750;850&family=Playfair+Display:wght@650;750&family=Poppins:wght@400;500;650;750;850&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ $profile->favicon_url ?: asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
    @stack('styles')
    <style>
        :root {
            --accent: {{ $accentColor }};
            --accent-ink: {{ $accentIsLight ? '#111614' : '#ffffff' }};
            --paper: {{ $paperColor }};
            --ink: {{ $paperIsDark ? '#f6faf7' : '#121614' }};
            --muted: {{ $paperIsDark ? '#c6d2cc' : '#5f6964' }};
            --surface: {{ $paperIsDark ? 'color-mix(in srgb, var(--paper) 86%, #ffffff 14%)' : '#ffffff' }};
            --surface-strong: {{ $paperIsDark ? 'color-mix(in srgb, var(--paper) 78%, #ffffff 22%)' : 'color-mix(in srgb, var(--paper) 84%, #ffffff 16%)' }};
            --line: {{ $paperIsDark ? 'color-mix(in srgb, var(--paper) 70%, #ffffff 30%)' : 'color-mix(in srgb, var(--paper) 84%, #121614 16%)' }};
            --section-bg: color-mix(in srgb, var(--paper) {{ $paperIsDark ? '88%' : '90%' }}, var(--surface) {{ $paperIsDark ? '12%' : '10%' }});
            --section-bg-soft: color-mix(in srgb, var(--paper) {{ $paperIsDark ? '72%' : '66%' }}, var(--surface) {{ $paperIsDark ? '28%' : '34%' }});
            --glass-bg: color-mix(in srgb, var(--surface) 86%, transparent);
            --tag-bg: color-mix(in srgb, var(--surface) 78%, transparent);
            --hero-wash: linear-gradient(135deg, color-mix(in srgb, var(--accent) 9%, transparent), color-mix(in srgb, var(--section-bg-soft) 82%, transparent)), var(--section-bg);
            --font-sans: {!! $fontConfig['body'] !!};
            --font-serif: {!! $fontConfig['display'] !!};
        }
    </style>
</head>
<body class="public-body">
    <header class="site-header" data-nav>
        <a class="brand" href="{{ route('portfolio.home') }}" aria-label="{{ $profile->owner_name }} home">
            @if ($profile->frontend_logo_url)
                <img src="{{ $profile->frontend_logo_url }}" alt="{{ $profile->owner_name }} logo">
            @else
                <span>{{ $profile->initials() }}</span>
            @endif
            <strong>{{ $profile->owner_name }}</strong>
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
        <nav class="site-nav" data-nav-menu>
            <a href="{{ route('portfolio.home') }}#services" data-section-link="services">Services</a>
            <a href="{{ route('portfolio.home') }}#experience" data-section-link="experience">Experience</a>
            <a href="{{ route('portfolio.home') }}#education" data-section-link="education">Education</a>
            <a href="{{ route('portfolio.home') }}#skills" data-section-link="skills">Skills</a>
            <a href="{{ route('portfolio.home') }}#work" data-section-link="work" @class(['active' => request()->routeIs('portfolio.projects.*')])>Projects</a>
            <a href="{{ route('portfolio.home') }}#publications" data-section-link="publications">Publications</a>
            <a href="{{ route('portfolio.blog.index') }}" @class(['active' => request()->routeIs('portfolio.blog.*')])>Blog</a>
            <a href="{{ route('contact.index') }}" @class(['active' => request()->routeIs('contact.index')])>Contact</a>
            <a class="nav-pill" href="{{ route('admin.dashboard') }}">Control</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    @php($whatsappUrl = $profile->whatsapp_url ?: $profile->phone)
    @if ($whatsappUrl && ! \Illuminate\Support\Str::startsWith($whatsappUrl, ['http://', 'https://']))
        @php($whatsappDigits = preg_replace('/\D+/', '', $whatsappUrl))
        @php($whatsappUrl = $whatsappDigits ? 'https://wa.me/'.$whatsappDigits : null)
    @endif

    <footer class="site-footer">
        <div class="footer-identity">
            @if ($profile->frontend_logo_url)
                <img width="65" src="{{ $profile->frontend_logo_url }}" alt="{{ $profile->owner_name }} logo">
            @else
                <span>{{ $profile->initials() }}</span>
            @endif
            <strong>{{ $profile->owner_name }}</strong>
            <a class="footer-email" href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
            <p class="footer-copy">&copy; {{ now()->year }} {{ $profile->owner_name }}. All rights reserved.</p>
        </div>
        <div class="footer-links" aria-label="Social links">
            @foreach ([
                'Facebook' => [$profile->facebook_url, 'M14 8h2V5h-2c-2.2 0-4 1.8-4 4v2H8v3h2v6h3v-6h2.5l.5-3h-3V9c0-.6.4-1 1-1z'],
                'WhatsApp' => [$whatsappUrl, 'M20 11.5a8 8 0 0 1-11.8 7L4 20l1.4-4.1A8 8 0 1 1 20 11.5zM9.6 8.6c.2-.5.4-.5.7-.5h.5c.2 0 .4.1.5.4l.6 1.4c.1.3.1.5-.1.7l-.4.5c-.1.1-.2.3 0 .5.4.8 1.2 1.6 2.1 2 .2.1.4.1.5-.1l.6-.7c.2-.2.4-.2.7-.1l1.4.7c.3.1.4.3.4.5 0 .6-.4 1.3-1 1.5-.6.3-1.6.2-2.8-.3-1.7-.7-3.2-2.2-4-3.9-.5-1.1-.6-2-.3-2.6z'],
                'GitHub' => [$profile->github_url, 'M8 19c-4 1.5-4-2-5-2m10 4v-3.9c0-1 .1-1.4-.5-1.9 1.8-.2 3.6-.9 3.6-4A3.1 3.1 0 0 0 20 6.9 3 3 0 0 0 19.9 4s-.9-.3-3 1.1a10.6 10.6 0 0 0-5.8 0C9 3.7 8.1 4 8.1 4A3 3 0 0 0 8 6.9a3.1 3.1 0 0 0-.8 2.2c0 3.1 1.9 3.8 3.6 4-.4.4-.6.9-.6 1.7V19'],
                'LinkedIn' => [$profile->linkedin_url, 'M5 9h3v10H5zM6.5 5.4a1.7 1.7 0 1 0 0 3.4 1.7 1.7 0 0 0 0-3.4zM11 9h3v1.5c.4-.8 1.4-1.8 3-1.8 3.2 0 3.8 2.1 3.8 4.8V19h-3v-5c0-1.2 0-2.7-1.7-2.7s-2 1.3-2 2.6V19H11z'],
                'X' => [$profile->twitter_url, 'M4 5l6.8 8.4L4.5 19h3.1l4.6-4.1 3.3 4.1H20l-7.1-8.8L18.8 5h-3.1l-4.3 3.8L8.4 5H4z'],
                'Dribbble' => [$profile->dribbble_url, 'M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm-7 7.8c2.5 0 5.1-.4 7.7-1.1m4.9-3.5c-1.7 2-4 3.4-6.8 4.1m-2.2 7.2c.8-3.1 2.7-5.8 5.7-8m5.4 3.3c-2.4-.7-5-.8-7.7-.2'],
                'Website' => [$profile->website_url, 'M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 0c2 2.2 3 4.8 3 8s-1 5.8-3 8m0-16c-2 2.2-3 4.8-3 8s1 5.8 3 8M4.5 12h15'],
            ] as $label => [$url, $path])
                @if ($url)
                    <a href="{{ $url }}" target="_blank" rel="noreferrer" aria-label="{{ $label }}" title="{{ $label }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="{{ $path }}"></path>
                        </svg>
                    </a>
                @endif
            @endforeach
        </div>
    </footer>

    @stack('scripts')
    <script src="{{ asset('js/portfolio.js') }}" defer></script>
</body>
</html>
