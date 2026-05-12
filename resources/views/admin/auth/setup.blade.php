<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php($siteProfile = \App\Models\Profile::site())
@php($fontConfig = $siteProfile->fontConfig())
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>First Admin / {{ $siteProfile->owner_name }} Portfolio</title>
    <link rel="icon" href="{{ $siteProfile->favicon_url ?: asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
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
            <h1>Create first admin</h1>
            <p>No admin users exist yet. Create the first account to enter the dashboard.</p>
        </section>
        <form class="login-card" method="post" action="{{ route('admin.setup.store') }}">
            @csrf
            @if ($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            <label>Name <input name="name" value="{{ old('name') }}" required autofocus></label>
            <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
            <label>Password <input type="password" name="password" required></label>
            <label>Password confirmation <input type="password" name="password_confirmation" required></label>
            <button class="btn primary" type="submit">Create admin account</button>
            <a class="back-link" href="{{ route('portfolio.home') }}">Back to portfolio</a>
        </form>
    </main>
</body>
</html>
