@extends('layouts.admin')

@section('title', 'New Blog Post')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Blog</p>
            <h1>New post</h1>
        </div>
    </div>

    @include('admin.blog.form', [
        'post' => $post,
        'action' => route('admin.blog.store'),
        'method' => 'POST',
    ])
@endsection
