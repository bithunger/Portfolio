@extends('layouts.portfolio')

@section('title', 'Newsletter Unsubscribe - '.$profile->owner_name)
@section('meta_description', 'Manage newsletter subscription for '.$profile->owner_name)

@section('content')
    <section class="page-hero contact-hero">
        <p class="eyebrow">Newsletter</p>
        <h1>You are unsubscribed</h1>
        <p>{{ $subscription?->email ?: 'This newsletter subscription' }} will no longer receive newsletter emails.</p>
    </section>

    <section class="section contact-section">
        <div>
            <p class="eyebrow">Subscription updated</p>
            <h2>No further action needed</h2>
            <p class="muted">You can subscribe again from any newsletter form if you change your mind.</p>
        </div>
        <div class="contact-form">
            <div class="notice success">Newsletter preference saved.</div>
            <a class="btn primary" href="{{ route('portfolio.home') }}">Back to portfolio</a>
        </div>
    </section>
@endsection
