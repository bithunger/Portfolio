@extends('layouts.admin')

@section('title', 'New Testimonial')

@section('content')
    <div class="admin-heading"><div><p class="eyebrow">Testimonials</p><h1>New testimonial</h1></div></div>
    @include('admin.testimonials.form', ['action' => route('admin.testimonials.store'), 'method' => 'POST'])
@endsection
