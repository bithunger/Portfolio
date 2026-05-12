@extends('layouts.admin')

@section('title', 'New Education')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Education</p><h1>New education</h1></div></div>
    @include('admin.education.form', [
        'educationEntry' => $educationEntry,
        'action' => route('admin.education.store'),
        'method' => 'POST',
    ])
@endsection
