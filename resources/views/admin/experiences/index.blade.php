@extends('layouts.admin')

@section('title', 'Experience')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Experience</p>
            <h1>Career timeline</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.experiences.create') }}">New role</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>Role</th><th>Company</th><th>Period</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($experiences as $experience)
                    <tr>
                        <td><strong>{{ $experience->role }}</strong><small>{{ $experience->summary }}</small></td>
                        <td>{{ $experience->company }}</td>
                        <td>{{ optional($experience->start_date)->format('M Y') }} - {{ $experience->is_current ? 'Present' : optional($experience->end_date)->format('M Y') }}</td>
                        <td><span class="chip">{{ $experience->active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.experiences.edit', $experience) }}">Edit</a>
                            <form method="post" action="{{ route('admin.experiences.destroy', $experience) }}" data-confirm="Delete this role?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No experience entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
