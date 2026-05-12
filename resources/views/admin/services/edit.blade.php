@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Services</p><h1>Edit service</h1></div></div>
    @include('admin.services.form', ['action' => route('admin.services.update', $service), 'method' => 'PUT'])
@endsection
