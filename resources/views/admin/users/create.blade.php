@extends('layouts.admin')

@section('title', 'New User')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Users</p><h1>New user</h1></div></div>
    @include('admin.users.form', ['action' => route('admin.users.store'), 'method' => 'POST'])
@endsection
