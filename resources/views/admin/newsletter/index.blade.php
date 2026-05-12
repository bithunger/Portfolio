@extends('layouts.admin')

@section('title', 'Newsletter')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Newsletter</p>
            <h1>Subscriber manager</h1>
        </div>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Subscriber</th>
                    <th>Source</th>
                    <th>Subscribed</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subscriptions as $subscription)
                    <tr>
                        <td>
                            <strong>{{ $subscription->name ?: 'Reader' }}</strong>
                            <small>{{ $subscription->email }}</small>
                        </td>
                        <td>{{ $subscription->source ?: 'blog' }}</td>
                        <td>{{ optional($subscription->subscribed_at)->format('M j, Y g:i A') ?: 'Unknown' }}</td>
                        <td class="actions">
                            <form method="post" action="{{ route('admin.newsletter.destroy', $subscription) }}" data-confirm="Remove this subscriber?">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No newsletter subscribers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
