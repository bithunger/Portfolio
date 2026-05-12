@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Testimonials</p><h1>Edit testimonial</h1></div></div>
    @include('admin.testimonials.form', ['action' => route('admin.testimonials.update', $testimonial), 'method' => 'PUT'])
@endsection
