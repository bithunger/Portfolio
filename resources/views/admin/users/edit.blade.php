@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Users</p><h1>Edit user</h1></div></div>
    @include('admin.users.form', ['action' => route('admin.users.update', $user), 'method' => 'PUT'])
@endsection
