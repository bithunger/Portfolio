<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php($siteProfile = \App\Models\Profile::site())
@php($fontConfig = $siteProfile->fontConfig())
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Owner Setup / {{ $siteProfile->owner_name }} Portfolio</title>
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
    <main class="login-shell compact-auth-shell">
        <section>
            <p class="eyebrow">{{ $siteProfile->owner_name }} Portfolio</p>
            <h1>Create owner account</h1>
            <p>Register the portfolio owner to enter the dashboard.</p>
        </section>
        <form class="login-card compact-auth-card" method="post" action="{{ route('admin.setup.store') }}">
            @csrf
            @if ($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            <label>Name <input name="name" value="{{ old('name') }}" required autofocus></label>
            <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
            <label>Contact <input name="contact" value="{{ old('contact') }}" required></label>
            <label>Password <input type="password" name="password" required></label>
            <label>Password confirmation <input type="password" name="password_confirmation" required></label>
            <button class="btn primary" type="submit">Create owner account</button>
            <a class="back-link" href="{{ route('portfolio.home') }}">Back to portfolio</a>
        </form>
    </main>
</body>
</html>
