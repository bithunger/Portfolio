@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Testimonials</p>
            <h1>Client proof</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.testimonials.create') }}">New testimonial</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead><tr><th>Client</th><th>Quote</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td><strong>{{ $testimonial->name }}</strong><small>{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</small></td>
                        <td>{{ \Illuminate\Support\Str::limit($testimonial->quote, 120) }}</td>
                        <td>
                            <span class="chip">{{ $testimonial->active ? 'Active' : 'Hidden' }}</span>
                            @if ($testimonial->featured)<span class="chip accent">Featured</span>@endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}">Edit</a>
                            <form method="post" action="{{ route('admin.testimonials.destroy', $testimonial) }}" data-confirm="Delete this testimonial?">
                                @csrf @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No testimonials yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
