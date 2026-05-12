@extends('layouts.admin')

@section('title', 'Education')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Education</p>
            <h1>Academic flow</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.education.create') }}">New education</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>Degree</th><th>Institution</th><th>Period</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($educationEntries as $educationEntry)
                    <tr>
                        <td><strong>{{ $educationEntry->degree }}</strong><small>{{ $educationEntry->summary }}</small></td>
                        <td>{{ $educationEntry->institution }}</td>
                        <td>{{ $educationEntry->start_year ?: 'Start' }} - {{ $educationEntry->end_year ?: 'Present' }}</td>
                        <td><span class="chip">{{ $educationEntry->active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.education.edit', $educationEntry) }}">Edit</a>
                            <form method="post" action="{{ route('admin.education.destroy', $educationEntry) }}" data-confirm="Delete this education entry?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No education entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
