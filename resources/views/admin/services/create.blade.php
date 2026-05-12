@extends('layouts.admin')

@section('title', 'New Service')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Services</p><h1>New service</h1></div></div>
    @include('admin.services.form', ['action' => route('admin.services.store'), 'method' => 'POST'])
@endsection
