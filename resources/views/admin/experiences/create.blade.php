@extends('layouts.admin')

@section('title', 'New Experience')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Experience</p><h1>New role</h1></div></div>
    @include('admin.experiences.form', ['action' => route('admin.experiences.store'), 'method' => 'POST'])
@endsection
