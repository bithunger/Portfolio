<section class="newsletter-panel">
    <div class="newsletter-copy">
        <span class="newsletter-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" role="img">
                <path d="M4 6.5h16v11H4z"></path>
                <path d="m5 8 7 5 7-5"></path>
            </svg>
        </span>
        <div>
            <p class="eyebrow">Newsletter</p>
            <h2>Sharper build notes</h2>
            <p>Short, practical notes on portfolio strategy, backend UX, and Laravel polish.</p>
        </div>
    </div>
    <form id="newsletter-form" class="newsletter-form" action="{{ route('newsletter.store') }}" method="post" data-async-form>
        @csrf
        <input type="hidden" name="source" value="blog">
        @if (session('newsletter_status'))
            <div class="notice success" data-form-status>{{ session('newsletter_status') }}</div>
        @else
            <div class="notice success" data-form-status hidden></div>
        @endif
        @if ($errors->any())
            <div class="notice error" data-form-error>{{ $errors->first() }}</div>
        @else
            <div class="notice error" data-form-error hidden></div>
        @endif
        <label>Name <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name"></label>
        <label>Email <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required></label>
        <button class="btn primary" type="submit">Subscribe</button>
    </form>
</section>
