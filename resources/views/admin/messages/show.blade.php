@extends('layouts.admin')

@section('title', 'Message')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Inbox</p>
            <h1>{{ $contactMessage->subject ?: 'Portfolio inquiry' }}</h1>
        </div>
        <a class="btn ghost" href="mailto:{{ $contactMessage->email }}">Reply</a>
    </div>

    <article class="message-panel">
        <dl>
            <div><dt>Name</dt><dd>{{ $contactMessage->name }}</dd></div>
            <div><dt>Email</dt><dd>{{ $contactMessage->email }}</dd></div>
            <div><dt>Company</dt><dd>{{ $contactMessage->company ?: 'Not provided' }}</dd></div>
            <div><dt>Received</dt><dd>{{ $contactMessage->created_at->format('M d, Y g:i A') }}</dd></div>
        </dl>
        <p>{!! nl2br(e($contactMessage->message)) !!}</p>
    </article>

    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.messages.index') }}">Back</a>
        <form method="post" action="{{ route('admin.messages.destroy', $contactMessage) }}" data-confirm="Delete this message?">
            @csrf
            @method('DELETE')
            <button class="btn danger" type="submit">Delete message</button>
        </form>
    </div>
@endsection
