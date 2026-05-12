@extends('layouts.admin')

@section('title', 'Services')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Services</p>
            <h1>Offerings</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.services.create') }}">New service</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>Service</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td><strong>{{ $service->title }}</strong><small>{{ $service->description }}</small></td>
                        <td><span class="chip">{{ $service->active ? 'Active' : 'Hidden' }}</span></td>
                        <td>{{ $service->display_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.services.edit', $service) }}">Edit</a>
                            <form method="post" action="{{ route('admin.services.destroy', $service) }}" data-confirm="Delete this service?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No services yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
