@extends('layouts.portfolio')

@section('title', $post->title.' - Blog - '.$profile->owner_name)
@section('meta_description', $post->excerpt)

@push('scripts')
    <script>
        window.Prism = window.Prism || {};
        window.Prism.manual = true;
        window.Prism.plugins = window.Prism.plugins || {};
        window.Prism.plugins.autoloader = {
            languages_path: 'https://cdn.jsdelivr.net/npm/prismjs@1/components/',
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1/components/prism-core.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1/plugins/autoloader/prism-autoloader.min.js" defer></script>
@endpush

@section('content')
    <article @class(['article-shell', 'has-cover' => $post->cover_image_url])>
        <section class="article-hero">
            <a class="back-link" href="{{ route('portfolio.blog.index') }}">Back to blog</a>
            <div class="article-hero-copy">
                <p class="eyebrow">{{ optional($post->published_at)->format('M j, Y') ?: 'Field note' }}</p>
                <h1>{{ $post->title }}</h1>
                <p>{{ $post->excerpt }}</p>
            </div>
        </section>

        @if ($post->cover_image_url)
            <aside class="article-cover-rail" aria-label="Article cover image">
                <figure class="article-hero-media">
                    <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}">
                </figure>
            </aside>
        @endif

        <section class="section article-body">
            <div class="article-content">
                @if ($post->body === strip_tags($post->body))
                    {!! nl2br(e($post->body)) !!}
                @else
                    {!! $post->body !!}
                @endif
            </div>
        </section>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="section blog-section">
            <div class="section-heading">
                <p class="eyebrow">More Notes</p>
                <h2>Keep reading</h2>
            </div>
            <div class="blog-grid compact">
                @foreach ($relatedPosts as $related)
                    <article class="blog-card">
                        @if ($related->cover_image_url)
                            <a class="blog-image" href="{{ route('portfolio.blog.show', $related) }}">
                                <img src="{{ $related->cover_image_url }}" alt="{{ $related->title }}">
                            </a>
                        @endif
                        <div class="blog-card-body">
                            <div class="card-meta">
                                <span>{{ optional($related->published_at)->format('M j, Y') ?: 'Field note' }}</span>
                            </div>
                            <h3><a href="{{ route('portfolio.blog.show', $related) }}">{{ $related->title }}</a></h3>
                            <p>{{ $related->excerpt }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section">
        @include('portfolio.partials.newsletter')
    </section>
@endsection
