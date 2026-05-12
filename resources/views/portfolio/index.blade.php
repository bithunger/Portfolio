@extends('layouts.portfolio')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            @if ($profile->availability)
                <p class="eyebrow availability-pill"><span aria-hidden="true"></span>{{ $profile->availability }}</p>
            @endif
            <h1>{{ $profile->owner_name }}</h1>
            <p class="hero-lede">{{ $profile->headline }}</p>
            @if ($profile->tagline)
                <p class="hero-note">{{ $profile->tagline }}</p>
            @endif
            <div class="hero-actions">
                <a class="btn primary" href="{{ route('contact.index') }}">Start a project</a>
                @if ($profile->resume_url)
                    <a class="btn ghost" href="{{ $profile->resume_url }}" target="_blank" rel="noreferrer">Download resume</a>
                @endif
            </div>
            <div class="signal-row">
                <span>{{ $projects->where('featured', true)->count() }} featured projects</span>
                <span>{{ $skillsByCategory->flatten()->count() }} skills</span>
                <span>{{ $profile->location }}</span>
            </div>
        </div>
        <div class="hero-media">
            @if ($profile->portrait_url)
                <img src="{{ $profile->portrait_url }}" alt="{{ $profile->owner_name }}">
            @else
                <div class="portrait-placeholder">{{ $profile->initials() }}</div>
            @endif
            <div class="media-panel">
                <span>{{ $profile->hero_panel_label ?: 'Portfolio Studio' }}</span>
                <strong>{{ $profile->hero_panel_text ?: $profile->email }}</strong>
            </div>
        </div>
    </section>

    @if ($profile->bio)
        <section class="section intro-section">
            <p><span>{{ $profile->bio }}</span></p>
        </section>
    @endif

    <section class="section band service-section" id="services">
        <div class="section-heading">
            <p class="eyebrow">Services</p>
            <h2>Ways to work together</h2>
        </div>
        <div class="service-grid">
            @foreach ($services as $service)
                <article class="service-card">
                    <span class="service-icon">{{ $service->icon ?: '01' }}</span>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->description }}</p>
                    @if ($service->deliverables)
                        <ul>
                            @foreach (array_slice($service->deliverables, 0, 4) as $deliverable)
                                <li>{{ $deliverable }}</li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            @endforeach
        </div>
    </section>

    <section class="section split-section flow-section" id="experience">
        <div class="sticky-section-title">
            <div class="section-heading">
                <p class="eyebrow">Work Experience</p>
                <h2>Professional path</h2>
            </div>
        </div>
        <div class="flow-list">
            @foreach ($experiences as $experience)
                <article class="flow-card">
                    <span class="flow-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-12 0h16v12H4V7zm6 5h4"></path></svg>
                    </span>
                    <div>
                        <time>
                            {{ optional($experience->start_date)->format('Y') ?: 'Now' }}
                            -
                            {{ $experience->is_current ? 'Present' : (optional($experience->end_date)->format('Y') ?: 'Now') }}
                        </time>
                        <h3>{{ $experience->role }}</h3>
                        <p class="muted">{{ $experience->company }} @if($experience->location) / {{ $experience->location }} @endif</p>
                        @if ($experience->summary)
                            <p>{{ $experience->summary }}</p>
                        @endif
                        @if ($experience->highlights)
                            <ul>
                                @foreach ($experience->highlights as $highlight)
                                    <li>{{ $highlight }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    @if ($educationEntries->isNotEmpty())
        <section class="section split-section flow-section education-section" id="education">
            <div class="sticky-section-title">
                <div class="section-heading">
                    <p class="eyebrow">Education</p>
                    <h2>Academic qualifications</h2>
                </div>
            </div>
            <div class="flow-list">
                @foreach ($educationEntries as $education)
                    <article class="flow-card">
                        <span class="flow-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24"><path d="M12 4l9 5-9 5-9-5 9-5zm-5 8v4c2.8 2 7.2 2 10 0v-4"></path></svg>
                        </span>
                        <div>
                            <time>{{ $education->start_year ?: 'Start' }} - {{ $education->end_year ?: 'Present' }}</time>
                            <h3>{{ $education->degree }}</h3>
                            <p class="muted">{{ $education->institution }} @if($education->location) / {{ $education->location }} @endif</p>
                            @if ($education->summary)
                                <p>{{ $education->summary }}</p>
                            @endif
                            @if ($education->highlights)
                                <ul>
                                    @foreach ($education->highlights as $highlight)
                                        <li>{{ $highlight }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section split-section" id="skills">
        <div class="sticky-section-title">
            <div class="section-heading">
                <p class="eyebrow">Skills</p>
                <h2>Tools, systems, and strengths</h2>
            </div>
        </div>
        <div class="skill-groups">
            @foreach ($skillsByCategory as $category => $skills)
                <div class="skill-group">
                    <h3>{{ $category }}</h3>
                    @foreach ($skills as $skill)
                        <div class="skill-meter">
                            <div>
                                <span>{{ $skill->name }}</span>
                                <span><span data-count-up="{{ $skill->proficiency }}">0</span>%</span>
                            </div>
                            <i data-skill-bar style="--skill-width: {{ $skill->proficiency }}%"></i>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="section project-section" id="work">
        <div class="section-heading">
            <p class="eyebrow">Selected Work</p>
            <h2>Recent launches and case studies</h2>
        </div>
        <div class="project-grid">
            @forelse ($projects as $project)
                <article class="project-card">
                    <a href="{{ route('portfolio.projects.show', $project) }}" class="project-image">
                        @if ($project->image_url)
                            <img src="{{ $project->image_url }}" alt="{{ $project->title }}">
                        @endif
                    </a>
                    <div class="project-card-body">
                        <div class="project-card-meta">
                            <div class="project-meta-left">
                                <span class="project-indicator" aria-hidden="true">
                                    <svg viewBox="0 0 24 24"><path d="M4 7h16v11H4V7zm4-3h8l2 3H6l2-3z"></path></svg>
                                </span>
                                <span>{{ $project->year }}</span>
                                @if ($project->featured)
                                    <span>Featured</span>
                                @endif
                            </div>
                            <span class="project-action-icons">
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
                            </span>
                        </div>
                        <h3><a href="{{ route('portfolio.projects.show', $project) }}">{{ $project->title }}</a></h3>
                        <p>{{ $project->summary }}</p>
                        @if ($project->tech_stack)
                            <div class="tag-row">
                                @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                                    <span>{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <p class="empty-state">No published projects yet.</p>
            @endforelse
        </div>
    </section>

    @if ($publications->isNotEmpty())
        <section class="section split-section publication-section" id="publications">
            <div class="sticky-section-title">
                <div class="section-heading">
                    <p class="eyebrow">Research & Papers</p>
                    <h2>Publications</h2>
                </div>
            </div>
            <div class="publication-list">
                @foreach ($publications as $publication)
                    <article class="publication-card">
                        <span class="publication-icon">{{ $publication->icon ?: 'RP' }}</span>
                        <div>
                            <p class="card-meta"><span>{{ $publication->year ?: 'Research' }}</span></p>
                            <h3>{{ $publication->title }}</h3>
                            <p>{{ $publication->journal_name ?: 'Journal' }} @if($publication->publisher) / {{ $publication->publisher }} @endif</p>
                        </div>
                        @if ($publication->article_url)
                            <a href="{{ $publication->article_url }}" target="_blank" rel="noreferrer" aria-label="Read {{ $publication->title }}">
                                <svg viewBox="0 0 24 24"><path d="M14 4h6v6M10 14L20 4M20 14v5H5V4h5"></path></svg>
                            </a>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($testimonials->isNotEmpty())
        <section class="section testimonial-section">
            <div class="testimonial-heading">
                <div class="section-heading">
                    <p class="eyebrow">Testimonials</p>
                    <h2>Client notes</h2>
                </div>
                <div class="carousel-controls">
                    <button type="button" aria-label="Slide testimonials left" data-testimonial-prev>&larr;</button>
                    <button type="button" aria-label="Slide testimonials right" data-testimonial-next>&rarr;</button>
                </div>
            </div>
            <div class="testimonial-carousel" data-testimonial-carousel>
                <div class="testimonial-track" data-testimonial-track>
                    @foreach ($testimonials->concat($testimonials) as $testimonial)
                        <article class="testimonial-card">
                            <img
                                src="{{ $testimonial->avatar_url ?: asset('images/avatar-placeholder.svg') }}"
                                alt="{{ $testimonial->name }}"
                                @class(['avatar-fallback' => ! $testimonial->avatar_url])
                                onerror="this.onerror=null;this.src='{{ asset('images/avatar-placeholder.svg') }}';this.classList.add('avatar-fallback');"
                            >
                            <p>{{ $testimonial->quote }}</p>
                            <span>
                                <strong>{{ $testimonial->name }}</strong>
                                <small>{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</small>
                            </span>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
