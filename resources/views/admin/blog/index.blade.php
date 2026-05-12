@extends('layouts.admin')

@section('title', 'Blog')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Blog</p>
            <h1>Writing library</h1>
        </div>
        <a class="btn primary" href="{{ route('admin.blog.create') }}">New post</a>
    </div>

    <div class="table-panel">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Published</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            <small>{{ $post->excerpt }}</small>
                        </td>
                        <td>{{ optional($post->published_at)->format('M j, Y') ?: 'Anytime' }}</td>
                        <td>
                            <span class="chip">{{ $post->published ? 'Published' : 'Draft' }}</span>
                            @if ($post->featured)<span class="chip accent">Featured</span>@endif
                        </td>
                        <td>{{ $post->display_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.blog.edit', $post) }}">Edit</a>
                            <form method="post" action="{{ route('admin.blog.destroy', $post) }}" data-confirm="Delete this blog post?">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No blog posts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
