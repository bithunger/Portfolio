@extends('layouts.admin')

@section('title', 'New Skill')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Skills</p><h1>New skill</h1></div></div>
    @include('admin.skills.form', ['action' => route('admin.skills.store'), 'method' => 'POST'])
@endsection
