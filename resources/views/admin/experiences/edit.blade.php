@extends('layouts.admin')

@section('title', 'Edit Experience')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Experience</p><h1>Edit role</h1></div></div>
    @include('admin.experiences.form', ['action' => route('admin.experiences.update', $experience), 'method' => 'PUT'])
@endsection
