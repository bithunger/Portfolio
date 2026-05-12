@extends('layouts.admin')

@section('title', 'Skills')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Skills</p>
            <h1>Capability map</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.skills.create') }}">New skill</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>Skill</th><th>Category</th><th>Level</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($skills as $skill)
                    <tr>
                        <td><strong>{{ $skill->name }}</strong></td>
                        <td>{{ $skill->category }}</td>
                        <td>{{ $skill->proficiency }}%</td>
                        <td><span class="chip">{{ $skill->active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.skills.edit', $skill) }}">Edit</a>
                            <form method="post" action="{{ route('admin.skills.destroy', $skill) }}" data-confirm="Delete this skill?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No skills yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
