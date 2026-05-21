@extends('layouts.portfolio')

@section('title', $project->title.' / '.$profile->owner_name)
@section('meta_description', $project->summary)

@section('content')
    <article @class(['article-shell', 'project-shell', 'has-cover' => $project->image_url])>
        <section class="article-hero project-article-hero">
            <a class="back-link" href="{{ route('portfolio.home') }}#projects">Back to all projects</a>
            <div class="article-hero-copy">
                <p class="eyebrow">{{ $project->client ?: 'Case Study' }}</p>
                <h1>{{ $project->title }}</h1>
                <p>{{ $project->summary }}</p>
                <div class="project-detail-meta">
                    @if ($project->role)<span><strong>Role</strong>{{ $project->role }}</span>@endif
                    @if ($project->year)<span><strong>Year</strong>{{ $project->year }}</span>@endif
                </div>
            </div>
        </section>

        @if ($project->image_url)
            <aside class="article-cover-rail project-cover-rail" aria-label="Project image">
                <figure class="article-hero-media project-hero-media">
                    <img src="{{ $project->image_url }}" alt="{{ $project->title }}">
                </figure>
            </aside>
        @endif

        <section class="section article-body project-article-body">
            <div class="project-overview">
                <div class="project-overview-head">
                    <h2>Overview</h2>
                    @if ($project->repo_url || $project->live_url)
                        <div class="project-overview-actions project-action-icons">
                            @if ($project->repo_url)
                                <a href="{{ $project->repo_url }}" target="_blank" rel="noreferrer" aria-label="{{ $project->title }} source">
                                    <svg viewBox="0 0 24 24"><path d="M8 19c-4 1.5-4-2-5-2m10 4v-3.9c0-1 .1-1.4-.5-1.9 1.8-.2 3.6-.9 3.6-4A3.1 3.1 0 0 0 20 6.9 3 3 0 0 0 19.9 4s-.9-.3-3 1.1a10.6 10.6 0 0 0-5.8 0C9 3.7 8.1 4 8.1 4A3 3 0 0 0 8 6.9a3.1 3.1 0 0 0-.8 2.2c0 3.1 1.9 3.8 3.6 4-.4.4-.6.9-.6 1.7V19"></path></svg>
                                </a>
                            @endif
                            @if ($project->live_url)
                                <a href="{{ $project->live_url }}" target="_blank" rel="noreferrer" aria-label="{{ $project->title }} live site">
                                    <svg viewBox="0 0 24 24"><path d="M14 4h6v6M10 14L20 4M20 14v5H5V4h5"></path></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <article class="project-overview-copy">
                    <div>{!! nl2br(e($project->description ?: $project->summary)) !!}</div>
                    @if ($project->tech_stack)
                        <div class="tag-row large">
                            @foreach ($project->tech_stack as $tech)
                                <span>{{ $tech }}</span>
                            @endforeach
                        </div>
                    @endif
                </article>
            </div>
        </section>
    </article>

    @if ($relatedProjects->isNotEmpty())
        <section class="section">
            <div class="section-heading">
                <p class="eyebrow">More Work</p>
                <h2>Related projects</h2>
            </div>
            <div class="project-grid compact">
                @foreach ($relatedProjects as $related)
                    <article class="project-card">
                        <a href="{{ route('portfolio.projects.show', $related) }}" class="project-image">
                            @if ($related->image_url)
                                <img src="{{ $related->image_url }}" alt="{{ $related->title }}">
                            @endif
                        </a>
                        <div class="project-card-body">
                            <h3><a href="{{ route('portfolio.projects.show', $related) }}">{{ $related->title }}</a></h3>
                            <p>{{ $related->summary }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
