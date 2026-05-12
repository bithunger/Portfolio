@extends('layouts.admin')

@section('title', 'Edit Publication')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Publications</p><h1>Edit publication</h1></div></div>
    @include('admin.publications.form', [
        'publication' => $publication,
        'action' => route('admin.publications.update', $publication),
        'method' => 'PUT',
    ])
@endsection
