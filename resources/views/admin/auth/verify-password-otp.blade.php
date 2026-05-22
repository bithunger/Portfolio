<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php($siteProfile = \App\Models\Profile::site())
@php($fontConfig = $siteProfile->fontConfig())
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Reset Code / {{ $siteProfile->owner_name }} Portfolio</title>
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
            <h1>Enter reset code</h1>
            <p>Check {{ $maskedEmail }} for the 4-digit code. The code expires in {{ $expiresInMinutes }} minutes.</p>
        </section>
        <form class="login-card" method="post" action="{{ route('admin.password.verify.store') }}">
            @csrf
            @if (session('status'))
                <div class="notice success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            <label>Reset code <input type="text" name="code" value="{{ old('code') }}" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{4}" maxlength="4" required autofocus></label>
            <button class="btn primary" type="submit">Verify code</button>
            <div class="login-options">
                <a class="text-link" href="{{ route('admin.password.request') }}">Use another email</a>
                <button class="text-link inline-link-button" type="submit" form="resend-reset-code">Send new code</button>
            </div>
        </form>
        <form id="resend-reset-code" method="post" action="{{ route('admin.password.email') }}" hidden>
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
        </form>
    </main>
</body>
</html>
