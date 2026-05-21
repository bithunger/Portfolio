<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php($siteProfile = \App\Models\Profile::site())
@php($fontConfig = $siteProfile->fontConfig())
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password / {{ $siteProfile->owner_name }} Portfolio</title>
    <link rel="icon" href="{{ $siteProfile->favicon_url ?: asset('favicon.ico') }}">
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
<body class="login-body">
    <main class="login-shell">
        <section>
            <p class="eyebrow">{{ $siteProfile->owner_name }} Portfolio</p>
            <h1>Reset password</h1>
            <p>Enter the current admin email address. If it matches an account, the password reset page will open.</p>
        </section>
        <form class="login-card" method="post" action="{{ route('admin.password.email') }}">
            @csrf
            @if (session('status'))
                <div class="notice success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            <label>Email <input type="email" name="email" value="{{ old('email') }}" required autofocus></label>
            <button class="btn primary" type="submit">Continue</button>
            <a class="back-link" href="{{ route('admin.login') }}">Back to sign in</a>
        </form>
    </main>
</body>
</html>
