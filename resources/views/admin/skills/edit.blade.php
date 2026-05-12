@extends('layouts.admin')

@section('title', 'Edit Skill')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Skills</p><h1>Edit skill</h1></div></div>
    @include('admin.skills.form', ['action' => route('admin.skills.update', $skill), 'method' => 'PUT'])
@endsection
