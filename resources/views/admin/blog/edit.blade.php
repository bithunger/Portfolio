@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Blog</p>
            <h1>Edit post</h1>
        </div>
    </div>

    @include('admin.blog.form', [
        'post' => $post,
        'action' => route('admin.blog.update', $post),
        'method' => 'PUT',
    ])
@endsection
