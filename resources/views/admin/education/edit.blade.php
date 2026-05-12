@extends('layouts.admin')

@section('title', 'Edit Education')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Education</p><h1>Edit education</h1></div></div>
    @include('admin.education.form', [
        'educationEntry' => $educationEntry,
        'action' => route('admin.education.update', $educationEntry),
        'method' => 'PUT',
    ])
@endsection
