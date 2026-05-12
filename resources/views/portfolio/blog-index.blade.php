@extends('layouts.portfolio')

@section('title', 'Blog / '.$profile->owner_name)
@section('meta_description', 'Articles and notes from '.$profile->owner_name)

@section('content')
    <section class="page-hero blog-list-hero">
        <p class="eyebrow">Blog</p>
        <h1>Notes for cleaner builds and sharper launches</h1>
        <p>Practical ideas on portfolio structure, backend UX, Laravel delivery, and client-ready product polish.</p>
    </section>

    <section class="section blog-section">
        <div class="blog-grid">
            @forelse ($posts as $post)
                <article @class(['blog-card', 'featured' => $loop->first && $post->cover_image_url])>
                    @if ($post->cover_image_url)
                        <a class="blog-image" href="{{ route('portfolio.blog.show', $post) }}">
                            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}">
                        </a>
                    @endif
                    <div class="blog-card-body">
                        <div class="card-meta">
                            <span>{{ optional($post->published_at)->format('M j, Y') ?: 'Field note' }}</span>
                            @if ($post->featured)
                                <span>Featured</span>
                            @endif
                        </div>
                        <h3><a href="{{ route('portfolio.blog.show', $post) }}">{{ $post->title }}</a></h3>
                        <p>{{ $post->excerpt }}</p>
                        <a class="text-link" href="{{ route('portfolio.blog.show', $post) }}">Read article</a>
                    </div>
                </article>
            @empty
                <p class="empty-state">No published articles yet.</p>
            @endforelse
        </div>
    </section>

    <section class="section">
        @include('portfolio.partials.newsletter')
    </section>
@endsection
