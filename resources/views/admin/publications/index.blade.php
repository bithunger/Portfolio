@extends('layouts.admin')

@section('title', 'Publications')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Publications</p>
            <h1>Research and papers</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.publications.create') }}">New publication</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Journal</th><th>Year</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($publications as $publication)
                    <tr>
                        <td><strong>{{ $publication->title }}</strong><small>{{ $publication->publisher }}</small></td>
                        <td>{{ $publication->journal_name ?: 'Research paper' }}</td>
                        <td>{{ $publication->year ?: 'Year' }}</td>
                        <td><span class="chip">{{ $publication->active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.publications.edit', $publication) }}">Edit</a>
                            <form method="post" action="{{ route('admin.publications.destroy', $publication) }}" data-confirm="Delete this publication?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No publications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
