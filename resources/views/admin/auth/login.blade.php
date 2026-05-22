<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php($siteProfile = \App\Models\Profile::site())
@php($fontConfig = $siteProfile->fontConfig())
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login / {{ $siteProfile->owner_name }} Portfolio</title>
    <link rel="icon" href="{{ $siteProfile->favicon_url ?: asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ \App\Support\VersionedAsset::url('css/portfolio.css') }}">
    <style>
        :root {
            --accent: {{ $siteProfile->accent_color ?: '#0f766e' }};
            --paper: {{ $siteProfile->backend_background_color ?: '#f7f8f6' }};
            --font-sans: {!! $fontConfig['body'] !!};
            --font-serif: {!! $fontConfig['display'] !!};
        }
    </style>
</head>
<body class="login-body">
    <main class="login-shell">
        <section>
            <p class="eyebrow">{{ $siteProfile->owner_name }} Portfolio</p>
            <h1>Admin dashboard</h1>
            <p>Manage portfolio content, messages, SEO text, featured work, blog posts, and admin access from one clean control room.</p>
        </section>
        <form class="login-card" method="post" action="{{ route('admin.login.store') }}">
            @csrf
            @if (session('status'))
                <div class="notice success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            <label>Email <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus></label>
            <label>Password <input type="password" name="password" required></label>
            <div class="login-options">
                <label class="check-row"><input type="checkbox" name="remember" value="1"> Remember me</label>
                <a class="text-link" href="{{ route('admin.password.request') }}">Forgot password?</a>
            </div>
            <button class="btn primary" type="submit">Sign in</button>
        </form>
    </main>
</body>
</html>
