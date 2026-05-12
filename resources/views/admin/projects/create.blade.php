@extends('layouts.admin')

@section('title', 'New Project')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Projects</p>
            <h1>New project</h1>
        </div>
    </div>

    @include('admin.projects.form', [
        'action' => route('admin.projects.store'),
        'method' => 'POST',
    ])
@endsection
