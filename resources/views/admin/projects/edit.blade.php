@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Projects</p>
            <h1>Edit project</h1>
        </div>
        <a class="btn ghost" href="{{ route('portfolio.projects.show', $project) }}" target="_blank" rel="noreferrer">View</a>
    </div>

    @include('admin.projects.form', [
        'action' => route('admin.projects.update', $project),
        'method' => 'PUT',
    ])
@endsection
