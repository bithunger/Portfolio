@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Users</p>
            <h1>Admin access</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.users.create') }}">New user</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>User</th><th>Email</th><th>Contact</th><th>Status</th><th>Updated</th><th></th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            <small>{{ $user->isOwner() ? 'Owner account' : (auth()->id() === $user->id ? 'Current account' : 'Admin user') }}</small>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact ?: 'Not provided' }}</td>
                        <td><span class="chip">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</span></td>
                        <td>{{ $user->updated_at?->format('M j, Y') }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            @if (! $user->isOwner() && auth()->id() !== $user->id && $users->count() > 1)
                                <form method="post" action="{{ route('admin.users.destroy', $user) }}" data-confirm="Delete this user?">
                                    @csrf @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No users yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
