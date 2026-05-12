@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Inbox</p>
            <h1>Contact messages</h1>
        </div>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>From</th><th>Subject</th><th>Received</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td><strong>{{ $message->name }}</strong><small>{{ $message->email }}</small></td>
                        <td>{{ $message->subject ?: 'Portfolio inquiry' }}</td>
                        <td>{{ $message->created_at->format('M d, Y') }}</td>
                        <td><span class="chip {{ $message->read_at ? '' : 'accent' }}">{{ $message->read_at ? 'Read' : 'Unread' }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.messages.show', $message) }}">Open</a>
                            <form method="post" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Delete this message?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
