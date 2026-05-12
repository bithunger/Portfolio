@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Projects</p>
            <h1>Work library</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.projects.create') }}">New project</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>
                            <strong>{{ $project->title }}</strong>
                            <small>{{ $project->summary }}</small>
                        </td>
                        <td>{{ $project->year }}</td>
                        <td>
                            <span class="chip">{{ $project->published ? 'Published' : 'Draft' }}</span>
                            @if ($project->featured)<span class="chip accent">Featured</span>@endif
                        </td>
                        <td>{{ $project->display_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                            <form method="post" action="{{ route('admin.projects.destroy', $project) }}" data-confirm="Delete this project?">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No projects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
