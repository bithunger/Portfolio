<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php($siteProfile = \App\Models\Profile::site())
@php($fontConfig = $siteProfile->fontConfig())
@php($unreadMessages = \App\Models\ContactMessage::whereNull('read_at')->count())
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ $siteProfile->owner_name }} Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;650;750;850&family=Lora:wght@500;650;700&family=Manrope:wght@400;500;650;750;850&family=Playfair+Display:wght@650;750&family=Poppins:wght@400;500;650;750;850&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ $siteProfile->favicon_url ?: asset('favicon.ico') }}">
    <script>
        (() => {
            try {
                if (window.matchMedia('(min-width: 1101px)').matches && window.localStorage.getItem('adminSidebarCollapsed') === 'true') {
                    document.documentElement.classList.add('admin-sidebar-collapsed-preload');
                }
            } catch (error) {}
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v={{ filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/portfolio.css') }}">
    <style>
        :root {
            --accent: {{ $siteProfile->accent_color ?: '#0f766e' }};
            --paper: {{ $siteProfile->backend_background_color ?: '#f7f8f6' }};
            --font-sans: {!! $fontConfig['body'] !!};
            --font-serif: {!! $fontConfig['display'] !!};
        }
    </style>
</head>
<body class="admin-body" data-admin-body>
    <aside class="admin-sidebar">
        <div class="sidebar-head">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                @if ($siteProfile->backend_logo_url)
                    <img src="{{ $siteProfile->backend_logo_url }}" alt="Admin logo">
                @else
                    <span>{{ $siteProfile->initials() ?: 'PS' }}</span>
                @endif
                <strong>Portfolio control room</strong>
            </a>
            <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-expanded="true" aria-label="Toggle sidebar">
                <span></span>
                <span></span>
            </button>
        </div>
        <nav>
            @foreach ([
                'Dashboard' => ['admin.dashboard', 'admin.dashboard', 'M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5zm10 0h6v-7h-6v7z'],
                'Profile' => ['admin.profile.edit', 'admin.profile.*', 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm-7 8c1.2-4 3.5-6 7-6s5.8 2 7 6'],
                'Users' => ['admin.users.index', 'admin.users.*', 'M16 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm8 2c2.6 0 4.4 1.3 5 4m-18 0c.7-3.4 2.5-5 5-5s4.3 1.6 5 5'],
                'Projects' => ['admin.projects.index', 'admin.projects.*', 'M4 7h16v11H4V7zm4-3h8l2 3H6l2-3z'],
                'Services' => ['admin.services.index', 'admin.services.*', 'M14.7 6.3l3 3-8.4 8.4H6.3v-3l8.4-8.4zM13 8l3 3'],
                'Skills' => ['admin.skills.index', 'admin.skills.*', 'M4 17l5-5 4 4 7-9M4 21h16'],
                'Experience' => ['admin.experiences.index', 'admin.experiences.*', 'M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-12 0h16v12H4V7zm6 5h4'],
                'Education' => ['admin.education.index', 'admin.education.*', 'M12 4l9 5-9 5-9-5 9-5zm-5 8v4c2.8 2 7.2 2 10 0v-4'],
                'Publications' => ['admin.publications.index', 'admin.publications.*', 'M6 4h10l2 2v14H6V4zm9 0v3h3M9 11h6M9 15h6M9 18h3'],
                'Testimonials' => ['admin.testimonials.index', 'admin.testimonials.*', 'M5 6h14v9H8l-3 3V6zm4 4h.1M12 10h3'],
                'Blog' => ['admin.blog.index', 'admin.blog.*', 'M6 4h9l3 3v13H6V4zm8 0v4h4M9 12h6M9 16h6'],
                'Newsletter' => ['admin.newsletter.index', 'admin.newsletter.*', 'M4 6h16v12H4V6zm0 2l8 5 8-5'],
                'Messages' => ['admin.messages.index', 'admin.messages.*', 'M4 5h16v11H7l-3 3V5zm5 5h6'],
            ] as $label => [$route, $pattern, $icon])
                <a href="{{ route($route) }}" title="{{ $label }}" @class(['active' => request()->routeIs($pattern)])>
                    <span class="admin-nav-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="{{ $icon }}"></path>
                        </svg>
                    </span>
                    <span class="admin-nav-label">{{ $label }}</span>
                    @if ($label === 'Messages' && $unreadMessages > 0)
                        <span class="admin-nav-badge" aria-label="{{ $unreadMessages }} unread messages">{{ $unreadMessages }}</span>
                    @endif
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <a href="{{ route('portfolio.home') }}" target="_blank" rel="noreferrer">View site</a>
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="link-button">Logout</button>
            </form>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    @if (session('status') || (isset($errors) && $errors->any()))
        <div class="toast-stack" data-toast-stack>
            @if (session('status'))
                <div class="toast success" role="status">
                    <span>{{ session('status') }}</span>
                    <button type="button" aria-label="Close message" data-toast-close>&times;</button>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="toast error" role="alert">
                    <span>{{ $errors->first() }}</span>
                    <button type="button" aria-label="Close message" data-toast-close>&times;</button>
                </div>
            @endif
        </div>
    @endif

    @stack('scripts')
    <script src="{{ asset('js/portfolio.js') }}?v={{ filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/portfolio.js') }}" defer></script>
</body>
</html>
