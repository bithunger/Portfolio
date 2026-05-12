@extends('layouts.admin')

@section('title', 'New Publication')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Publications</p><h1>New publication</h1></div></div>
    @include('admin.publications.form', [
        'publication' => $publication,
        'action' => route('admin.publications.store'),
        'method' => 'POST',
    ])
@endsection
