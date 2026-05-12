@extends('layouts.portfolio')

@section('title', 'Contact - '.$profile->owner_name)
@section('meta_description', 'Contact '.$profile->owner_name.' about a project')

@section('content')
    <section class="page-hero contact-hero">
        <p class="eyebrow">Contact</p>
        <h1>Tell me what you are building</h1>
        <p>{{ $profile->email }} @if ($profile->phone) / {{ $profile->phone }} @endif</p>
    </section>

    <section class="section contact-section">
        <div>
            <p class="eyebrow">Project inquiry</p>
            <h2>Share the useful details</h2>
            <p class="muted">A clear note helps me reply with the right next step, timeline, and questions.</p>
            <div class="contact-quick-links" aria-label="Contact details">
                <a href="mailto:{{ $profile->email }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6.5h16v11H4z"></path><path d="m5 8 7 5 7-5"></path></svg>
                    <span>{{ $profile->email }}</span>
                </a>
                @if ($profile->location)
                    <span>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-5.2 7-11a7 7 0 0 0-14 0c0 5.8 7 11 7 11z"></path><path d="M12 10.5h.01"></path></svg>
                        <span>{{ $profile->location }}</span>
                    </span>
                @endif
                @if ($profile->phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $profile->phone) }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1A19.4 19.4 0 0 1 5.2 12 19.8 19.8 0 0 1 2.1 3.4 2 2 0 0 1 4.1 1.2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.4 2.1L8.1 9.2a16 16 0 0 0 6.7 6.7l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2z"></path></svg>
                        <span>{{ $profile->phone }}</span>
                    </a>
                @endif
            </div>
        </div>
        <form class="contact-form" action="{{ route('contact.store') }}" method="post">
            @csrf
            @if (session('status'))
                <div class="notice success">{{ session('status') }}</div>
            @endif
            <label>Name <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required></label>
            <label>Email <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required></label>
            <label>Company <input type="text" name="company" value="{{ old('company') }}" placeholder="Company or studio name"></label>
            <label>Subject <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Project type or topic"></label>
            <label class="full">Message <textarea name="message" rows="5" placeholder="Tell me about the goal, timeline, budget range, and useful links." required>{{ old('message') }}</textarea></label>
            @if ($errors->any())
                <div class="notice error">{{ $errors->first() }}</div>
            @endif
            <button class="btn primary" type="submit">Send message</button>
        </form>
    </section>
@endsection
