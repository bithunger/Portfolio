@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php($statIcons = [
        'Projects' => 'M4 7h16v11H4V7zm4-3h8l2 3H6l2-3z',
        'Services' => 'M14.7 6.3l3 3-8.4 8.4H6.3v-3l8.4-8.4zM13 8l3 3',
        'Skills' => 'M12 3l2.7 5.5 6 .9-4.3 4.2 1 6-5.4-3.1-5.4 3.1 1-6-4.3-4.2 6-.9L12 3z',
        'Experience' => 'M4 8h16v11H4V8zm5 0V5h6v3M4 13h16',
        'Education' => 'M12 4l9 5-9 5-9-5 9-5zm-5 8v4c2.8 2 7.2 2 10 0v-4',
        'Publications' => 'M6 4h10l2 2v14H6V4zm9 0v3h3M9 11h6M9 15h6M9 18h3',
        'Testimonials' => 'M5 6h14v9H8l-3 3V6zm5 4h6M8 10h.1',
        'Blog' => 'M6 4h9l3 3v13H6V4zm8 0v4h4M9 12h6M9 16h6',
        'Newsletter' => 'M4 6h16v12H4V6zm0 2l8 5 8-5',
        'Users' => 'M16 19v-1.5c0-1.9-1.8-3.5-4-3.5s-4 1.6-4 3.5V19M12 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
        'Unread' => 'M4 5h16v12H8l-4 3V5zm5 5h6M9 13h3',
    ])

    <div class="admin-heading">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>Portfolio control room</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.projects.create') }}">New project</a>
    </div>

    <section class="stat-grid">
        @foreach ($stats as $label => $value)
            <article class="stat-card">
                <div class="stat-card-head">
                    <span class="stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="{{ $statIcons[$label] ?? $statIcons['Projects'] }}"></path>
                        </svg>
                    </span>
                    <strong data-count-up="{{ $value }}" data-count-mode="stat">0</strong>
                </div>
                <span class="stat-label">{{ $label }}</span>
            </article>
        @endforeach
    </section>

    <section class="admin-grid two">
        <div class="panel">
            <div class="panel-heading">
                <h2>Featured work</h2>
                <a href="{{ route('admin.projects.index') }}">Manage</a>
            </div>
            <div class="list-stack">
                @forelse ($featuredProjects as $project)
                    <div class="list-row">
                        <span>
                            <strong>{{ $project->title }}</strong>
                            <small>{{ $project->published ? 'Published' : 'Draft' }}{{ $project->featured ? ' / Featured' : '' }}</small>
                        </span>
                        <a href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                    </div>
                @empty
                    <p class="empty-state">No projects yet.</p>
                @endforelse
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h2>Recent messages</h2>
                <a href="{{ route('admin.messages.index') }}">Inbox</a>
            </div>
            <div class="list-stack">
                @forelse ($recentMessages as $message)
                    <div class="list-row">
                        <span>
                            <strong>{{ $message->name }}</strong>
                            <small>{{ $message->subject ?: $message->email }}</small>
                        </span>
                        <a href="{{ route('admin.messages.show', $message) }}">{{ $message->read_at ? 'Open' : 'Unread' }}</a>
                    </div>
                @empty
                    <p class="empty-state">No messages yet.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
